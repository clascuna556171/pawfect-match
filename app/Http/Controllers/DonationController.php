<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DonationController extends Controller
{
    public function create(Request $request)
    {
        $petId = $request->integer('pet_id');
        $pet = $petId ? Pet::findOrFail($petId) : null;

        return view('donations.create', compact('pet'));
    }

    public function store(Request $request)
    {
        $normalizedGcashMobile = $this->normalizeGcashMobile((string) $request->input('gcash_mobile', ''));

        $request->merge([
            'card_number' => preg_replace('/\D+/', '', (string) $request->input('card_number', '')),
            'card_expiry' => strtoupper(trim((string) $request->input('card_expiry', ''))),
            'card_cvv' => preg_replace('/\D+/', '', (string) $request->input('card_cvv', '')),
            'bank_account_number' => preg_replace('/\D+/', '', (string) $request->input('bank_account_number', '')),
            'bank_routing_number' => preg_replace('/\D+/', '', (string) $request->input('bank_routing_number', '')),
            'gcash_mobile' => $normalizedGcashMobile,
            'gcash_reference' => strtoupper(trim((string) $request->input('gcash_reference', ''))),
            'paypal_email' => trim((string) $request->input('paypal_email', '')),
            'paypal_transaction_id' => strtoupper(trim((string) $request->input('paypal_transaction_id', ''))),
        ]);

        $validated = $request->validate([
            'pet_id' => ['nullable', 'exists:pets,id'],
            'donor_name' => ['required', 'string', 'max:255'],
            'donor_email' => ['required', 'email', 'max:255'],
            'amount' => ['required', 'numeric', 'min:1', 'max:500000'],
            'currency' => ['nullable', 'in:USD,PHP'],
            'payment_method' => ['required', 'in:Card,Bank Transfer,GCash,PayPal,Cash,Manual'],
            'message' => ['nullable', 'string', 'max:2000'],
            'is_anonymous' => ['nullable', 'boolean'],
            'card_number' => [
                Rule::requiredIf(fn () => $request->input('payment_method') === 'Card'),
                'nullable',
                'regex:/^\d{16}$/',
            ],
            'card_expiry' => [
                Rule::requiredIf(fn () => $request->input('payment_method') === 'Card'),
                'nullable',
                'regex:/^(0[1-9]|1[0-2])\/\d{2}$/',
            ],
            'card_cvv' => [
                Rule::requiredIf(fn () => $request->input('payment_method') === 'Card'),
                'nullable',
                'regex:/^\d{3,4}$/',
            ],
            'bank_account_number' => [
                Rule::requiredIf(fn () => $request->input('payment_method') === 'Bank Transfer'),
                'nullable',
                'regex:/^\d{8,17}$/',
            ],
            'bank_routing_number' => [
                Rule::requiredIf(fn () => $request->input('payment_method') === 'Bank Transfer'),
                'nullable',
                'regex:/^\d{9}$/',
            ],
            'gcash_mobile' => [
                Rule::requiredIf(fn () => $request->input('payment_method') === 'GCash'),
                'nullable',
                'regex:/^(09\d{9}|639\d{9}|\+639\d{9})$/',
            ],
            'gcash_reference' => [
                Rule::requiredIf(fn () => $request->input('payment_method') === 'GCash'),
                'nullable',
                'regex:/^[A-Z0-9\-]{8,30}$/',
            ],
            'paypal_email' => [
                Rule::requiredIf(fn () => $request->input('payment_method') === 'PayPal'),
                'nullable',
                'email',
                'max:255',
            ],
            'paypal_transaction_id' => [
                Rule::requiredIf(fn () => $request->input('payment_method') === 'PayPal'),
                'nullable',
                'regex:/^[A-Z0-9\-]{8,30}$/',
            ],
        ]);

        if (($validated['payment_method'] ?? null) === 'Card') {
            if (!$this->passesLuhn($validated['card_number'])) {
                return back()
                    ->withErrors(['card_number' => 'Card number is invalid.'])
                    ->withInput();
            }

            if (!$this->isFutureExpiry($validated['card_expiry'])) {
                return back()
                    ->withErrors(['card_expiry' => 'Card expiration must be in the future.'])
                    ->withInput();
            }
        }

        if (($validated['payment_method'] ?? null) === 'Bank Transfer') {
            if (!$this->isValidAbaRouting($validated['bank_routing_number'])) {
                return back()
                    ->withErrors(['bank_routing_number' => 'Routing number failed checksum validation.'])
                    ->withInput();
            }
        }

        $isAsyncPayment = in_array($validated['payment_method'], ['Bank Transfer', 'GCash', 'PayPal'], true);

        $donation = Donation::create([
            'user_id' => auth()->id(),
            'pet_id' => $validated['pet_id'] ?? null,
            'donor_name' => $validated['donor_name'],
            'donor_email' => $validated['donor_email'],
            'amount' => $validated['amount'],
            'currency' => $validated['currency'] ?? 'USD',
            'is_anonymous' => (bool) ($validated['is_anonymous'] ?? false),
            'payment_method' => $validated['payment_method'],
            'payment_reference' => $this->buildPaymentReference($validated),
            'status' => $isAsyncPayment ? 'Pending' : 'Confirmed',
            'message' => $validated['message'] ?? null,
            'donated_at' => now(),
        ]);

        if (auth()->check()) {
            return redirect()
                ->route('donations.index')
                ->with('success', 'Thank you for your donation. Your support changes lives.');
        }

        return redirect()
            ->route('donations.create', ['pet_id' => $donation->pet_id])
            ->with('success', 'Thank you for your donation. Your support changes lives.');
    }

    private function buildPaymentReference(array $validated): ?string
    {
        if (($validated['payment_method'] ?? null) === 'Card' && !empty($validated['card_number'])) {
            return 'Card ending ' . substr($validated['card_number'], -4);
        }

        if (($validated['payment_method'] ?? null) === 'Bank Transfer' && !empty($validated['bank_account_number']) && !empty($validated['bank_routing_number'])) {
            return 'Bank acct ****' . substr($validated['bank_account_number'], -4)
                . ' / routing ****' . substr($validated['bank_routing_number'], -4);
        }

        if (($validated['payment_method'] ?? null) === 'GCash' && !empty($validated['gcash_mobile']) && !empty($validated['gcash_reference'])) {
            $mobileDigits = preg_replace('/\D+/', '', $validated['gcash_mobile']);

            return 'GCash ' . substr($mobileDigits, -4)
                . ' / ref ' . $validated['gcash_reference'];
        }

        if (($validated['payment_method'] ?? null) === 'PayPal' && !empty($validated['paypal_email']) && !empty($validated['paypal_transaction_id'])) {
            $emailParts = explode('@', $validated['paypal_email']);
            $local = $emailParts[0] ?? 'payer';
            $domain = $emailParts[1] ?? 'paypal.com';
            $maskedLocal = strlen($local) <= 2 ? str_repeat('*', strlen($local)) : substr($local, 0, 2) . str_repeat('*', max(1, strlen($local) - 2));

            return 'PayPal ' . $maskedLocal . '@' . $domain
                . ' / tx ' . $validated['paypal_transaction_id'];
        }

        return null;
    }

    private function passesLuhn(string $number): bool
    {
        $sum = 0;
        $alternate = false;

        for ($index = strlen($number) - 1; $index >= 0; $index--) {
            $digit = (int) $number[$index];

            if ($alternate) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $sum += $digit;
            $alternate = !$alternate;
        }

        return $sum > 0 && $sum % 10 === 0;
    }

    private function isFutureExpiry(string $expiry): bool
    {
        [$month, $year] = explode('/', $expiry);
        $currentYear = (int) now()->format('y');
        $currentMonth = (int) now()->format('m');
        $expiryMonth = (int) $month;
        $expiryYear = (int) $year;

        if ($expiryYear < $currentYear) {
            return false;
        }

        if ($expiryYear === $currentYear && $expiryMonth < $currentMonth) {
            return false;
        }

        return true;
    }

    private function isValidAbaRouting(string $routing): bool
    {
        if (!preg_match('/^\d{9}$/', $routing)) {
            return false;
        }

        $digits = array_map('intval', str_split($routing));
        $checksum = (
            3 * ($digits[0] + $digits[3] + $digits[6]) +
            7 * ($digits[1] + $digits[4] + $digits[7]) +
            1 * ($digits[2] + $digits[5] + $digits[8])
        ) % 10;

        return $checksum === 0;
    }

    private function normalizeGcashMobile(string $mobile): string
    {
        $digits = preg_replace('/\D+/', '', $mobile);

        if (str_starts_with($digits, '09') && strlen($digits) === 11) {
            return $digits;
        }

        if (str_starts_with($digits, '639') && strlen($digits) === 12) {
            return '+' . $digits;
        }

        if (str_starts_with($mobile, '+') && str_starts_with($digits, '639') && strlen($digits) === 12) {
            return '+' . $digits;
        }

        return trim($mobile);
    }

    public function index()
    {
        $donations = Donation::with('pet')
            ->where('user_id', auth()->id())
            ->latest('donated_at')
            ->latest('id')
            ->paginate(12);

        $totalDonated = Donation::where('user_id', auth()->id())
            ->where('status', 'Confirmed')
            ->sum('amount');

        return view('donations.index', compact('donations', 'totalDonated'));
    }
}
