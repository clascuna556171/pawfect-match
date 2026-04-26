@extends('app')

@section('title', 'Support Rescue Donations - PawfectMatch')

@section('styles')
<style>
    .donation-page {
        max-width: 980px;
        margin: 0 auto;
        padding: 7.5rem 1.5rem 3rem;
    }

    .donation-shell {
        background: #fff;
        border: 1px solid rgba(26, 35, 50, 0.08);
        border-radius: 20px;
        box-shadow: 0 16px 30px rgba(26, 35, 50, 0.08);
        overflow: hidden;
    }

    .donation-header {
        padding: 2rem;
        border-bottom: 1px solid rgba(26, 35, 50, 0.08);
        background: linear-gradient(120deg, #fff7f5, #f5fffd);
        display: grid;
        grid-template-columns: 120px 1fr;
        gap: 1.25rem;
        align-items: center;
    }

    .donation-header img {
        width: 120px;
        height: 120px;
        border-radius: 16px;
        object-fit: cover;
    }

    .donation-header h1 {
        margin: 0 0 0.35rem;
        font-family: var(--font-serif);
        color: var(--navy);
        font-size: clamp(1.7rem, 3.2vw, 2.2rem);
    }

    .donation-header p {
        margin: 0;
        color: #475569;
        line-height: 1.6;
    }

    .donation-form {
        padding: 2rem;
        display: grid;
        gap: 1.25rem;
    }

    .donation-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
    }

    .field {
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
    }

    .field.full {
        grid-column: 1 / -1;
    }

    .field label {
        font-size: 0.9rem;
        font-weight: 600;
        color: #1f2937;
    }

    .field input,
    .field select,
    .field textarea {
        border: 1px solid #d1d5db;
        border-radius: 10px;
        padding: 0.75rem 0.85rem;
        font-size: 0.95rem;
        font-family: var(--font-sans);
        transition: border-color 180ms, box-shadow 180ms;
    }

    .field textarea {
        min-height: 120px;
        resize: vertical;
    }

    .field input:focus,
    .field select:focus,
    .field textarea:focus {
        outline: none;
        border-color: var(--coral);
        box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.16);
    }

    .error-text {
        color: #b91c1c;
        font-size: 0.84rem;
    }

    .alert-success {
        padding: 0.85rem 1rem;
        border-radius: 12px;
        border: 1px solid #a7f3d0;
        background: #ecfdf5;
        color: #065f46;
    }

    .alert-error {
        padding: 0.95rem 1rem;
        border-radius: 12px;
        border: 1px solid #fecaca;
        background: #fef2f2;
        color: #991b1b;
        line-height: 1.5;
    }

    .check-row {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #334155;
        font-weight: 500;
    }

    .actions {
        display: flex;
        justify-content: flex-end;
    }

    .payment-fields {
        border: 1px solid rgba(26, 35, 50, 0.08);
        background: #f8fafc;
        border-radius: 12px;
        padding: 0.9rem;
        display: none;
        gap: 1rem;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .payment-fields.visible {
        display: grid;
    }

    .payment-fields .field.full {
        grid-column: 1 / -1;
    }

    .gateway-note {
        font-size: 0.82rem;
        color: #475569;
        margin: 0;
    }

    .qr-panel {
        border: 1px dashed #cbd5e1;
        border-radius: 10px;
        background: #fff;
        padding: 0.8rem;
        display: grid;
        justify-items: center;
        gap: 0.55rem;
    }

    .qr-panel img {
        width: 180px;
        height: 180px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #fff;
    }

    .qr-label {
        font-size: 0.8rem;
        color: #334155;
        text-align: center;
        line-height: 1.4;
    }

    .btn-submit {
        border: none;
        cursor: pointer;
        color: #fff;
        font-weight: 700;
        border-radius: 10px;
        padding: 0.76rem 1.3rem;
        background: linear-gradient(135deg, #ff6b6b, #ff8e8e);
        box-shadow: 0 10px 18px rgba(255, 107, 107, 0.25);
    }

    @media (max-width: 768px) {
        .donation-header {
            grid-template-columns: 1fr;
        }

        .donation-header img {
            width: 100%;
            height: 220px;
        }

        .donation-grid {
            grid-template-columns: 1fr;
        }

        .actions {
            justify-content: stretch;
        }

        .payment-fields {
            grid-template-columns: 1fr;
        }

        .btn-submit {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="donation-page">
    <div class="donation-shell">
        <div class="donation-header">
            <img src="{{ $pet?->image_url ? asset('images/pets/' . $pet->image_url) : asset('images/logo.png') }}" alt="{{ $pet?->name ?? 'PawfectMatch Logo' }}" style="{{ !$pet ? 'object-fit: contain; padding: 10px; background: white;' : '' }}">
            <div>
                <h1>{{ $pet ? 'Donate for ' . $pet->name : 'Support Rescue Donations' }}</h1>
                <p>Your donation helps cover food, vaccines, medical treatment, and shelter operations while pets wait for a forever home.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('donations.store') }}" class="donation-form">
            @csrf
            <input type="hidden" name="pet_id" value="{{ old('pet_id', $pet?->id) }}">

            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert-error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="donation-grid">
                <div class="field">
                    <label for="donor_name">Full name</label>
                    <input id="donor_name" type="text" name="donor_name" value="{{ old('donor_name', auth()->user()->name ?? '') }}" required>
                    @error('donor_name')<span class="error-text">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label for="donor_email">Email address</label>
                    <input id="donor_email" type="email" name="donor_email" value="{{ old('donor_email', auth()->user()->email ?? '') }}" required>
                    @error('donor_email')<span class="error-text">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label for="amount">Amount</label>
                    <input id="amount" type="number" name="amount" min="1" step="0.01" value="{{ old('amount') }}" placeholder="e.g. 50" required>
                    @error('amount')<span class="error-text">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label for="currency">Currency</label>
                    <select id="currency" name="currency">
                        <option value="USD" {{ old('currency', 'USD') === 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="PHP" {{ old('currency') === 'PHP' ? 'selected' : '' }}>PHP</option>
                    </select>
                    @error('currency')<span class="error-text">{{ $message }}</span>@enderror
                </div>

                <div class="field full">
                    <label for="payment_method">Payment method</label>
                    <select id="payment_method" name="payment_method" required>
                        @foreach(['Card', 'Bank Transfer', 'GCash', 'PayPal', 'Cash', 'Manual'] as $method)
                            <option value="{{ $method }}" {{ old('payment_method', 'Manual') === $method ? 'selected' : '' }}>{{ $method }}</option>
                        @endforeach
                    </select>
                    @error('payment_method')<span class="error-text">{{ $message }}</span>@enderror
                </div>

                <section class="payment-fields" id="card-fields" data-method="Card">
                    <div class="field full">
                        <p class="gateway-note">Card payments require valid card details and are checked using industry-standard format and checksum rules.</p>
                    </div>
                    <div class="field full">
                        <label for="card_number">Card number</label>
                        <input id="card_number" type="text" name="card_number" inputmode="numeric" autocomplete="cc-number" maxlength="19" placeholder="1234 5678 9012 3456" value="{{ old('card_number') }}">
                        @error('card_number')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label for="card_expiry">Expiration (MM/YY)</label>
                        <input id="card_expiry" type="text" name="card_expiry" inputmode="numeric" autocomplete="cc-exp" maxlength="5" placeholder="MM/YY" value="{{ old('card_expiry') }}">
                        @error('card_expiry')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label for="card_cvv">CVV</label>
                        <input id="card_cvv" type="password" name="card_cvv" inputmode="numeric" autocomplete="cc-csc" maxlength="4" placeholder="3-4 digits" value="{{ old('card_cvv') }}">
                        @error('card_cvv')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                </section>

                <section class="payment-fields" id="bank-fields" data-method="Bank Transfer">
                    <div class="field full">
                        <p class="gateway-note">Bank transfers require a valid account number and a 9-digit routing number with checksum verification.</p>
                    </div>
                    <div class="field">
                        <label for="bank_account_number">Account number</label>
                        <input id="bank_account_number" type="text" name="bank_account_number" inputmode="numeric" maxlength="17" placeholder="8 to 17 digits" value="{{ old('bank_account_number') }}">
                        @error('bank_account_number')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label for="bank_routing_number">Routing number</label>
                        <input id="bank_routing_number" type="text" name="bank_routing_number" inputmode="numeric" maxlength="9" placeholder="9 digits" value="{{ old('bank_routing_number') }}">
                        @error('bank_routing_number')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                </section>

                <section class="payment-fields" id="gcash-fields" data-method="GCash">
                    <div class="field full">
                        <p class="gateway-note">Use GCash to send your donation, then provide the mobile number used and your transaction reference.</p>
                    </div>
                    <div class="field full">
                        <label for="gcash_mobile">GCash mobile number</label>
                        <input id="gcash_mobile" type="text" name="gcash_mobile" inputmode="tel" maxlength="14" placeholder="09XXXXXXXXX / 639XXXXXXXXX / +639XXXXXXXXX" value="{{ old('gcash_mobile') }}">
                        <span class="gateway-note">Accepted formats: 09XXXXXXXXX, 639XXXXXXXXX, or +639XXXXXXXXX.</span>
                        @error('gcash_mobile')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label for="gcash_reference">GCash transaction reference</label>
                        <input id="gcash_reference" type="text" name="gcash_reference" maxlength="30" placeholder="e.g. 7Q8A2KJ9" value="{{ old('gcash_reference') }}">
                        @error('gcash_reference')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="field full">
                        <div class="qr-panel">
                            <img id="gcash-qr" alt="GCash QR code" src="">
                            <p class="qr-label">Scan to pay via GCash to merchant number +639171234567. QR updates with your donation amount.</p>
                        </div>
                    </div>
                </section>

                <section class="payment-fields" id="paypal-fields" data-method="PayPal">
                    <div class="field full">
                        <p class="gateway-note">Use PayPal checkout/QR, then enter the payer email and PayPal transaction ID for verification.</p>
                    </div>
                    <div class="field">
                        <label for="paypal_email">PayPal payer email</label>
                        <input id="paypal_email" type="email" name="paypal_email" maxlength="255" placeholder="name@example.com" value="{{ old('paypal_email') }}">
                        @error('paypal_email')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label for="paypal_transaction_id">PayPal transaction ID</label>
                        <input id="paypal_transaction_id" type="text" name="paypal_transaction_id" maxlength="30" placeholder="e.g. 2AB12345CD678901E" value="{{ old('paypal_transaction_id') }}">
                        @error('paypal_transaction_id')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="field full">
                        <div class="qr-panel">
                            <img id="paypal-qr" alt="PayPal QR code" src="">
                            <p class="qr-label">Scan to pay via PayPal to paypal.me/pawfectmatch. QR updates with your donation amount.</p>
                        </div>
                    </div>
                </section>

                <div class="field full">
                    <label for="message">Message (optional)</label>
                    <textarea id="message" name="message" placeholder="You can dedicate this donation or share encouragement for the rescue team.">{{ old('message') }}</textarea>
                    @error('message')<span class="error-text">{{ $message }}</span>@enderror
                </div>

                <div class="field full">
                    <label class="check-row">
                        <input type="checkbox" name="is_anonymous" value="1" {{ old('is_anonymous') ? 'checked' : '' }}>
                        Keep this donation anonymous
                    </label>
                </div>
            </div>

            <div class="actions">
                <button type="submit" class="btn-submit">Complete Donation</button>
            </div>
        </form>
    </div>
</div>

<script>
    const paymentMethod = document.getElementById('payment_method');
    const amountInput = document.getElementById('amount');
    const cardFields = document.getElementById('card-fields');
    const bankFields = document.getElementById('bank-fields');
    const gcashFields = document.getElementById('gcash-fields');
    const paypalFields = document.getElementById('paypal-fields');
    const cardInputs = cardFields ? cardFields.querySelectorAll('input') : [];
    const bankInputs = bankFields ? bankFields.querySelectorAll('input') : [];
    const gcashInputs = gcashFields ? gcashFields.querySelectorAll('input') : [];
    const paypalInputs = paypalFields ? paypalFields.querySelectorAll('input') : [];

    const gcashQr = document.getElementById('gcash-qr');
    const paypalQr = document.getElementById('paypal-qr');

    function setRequired(inputs, required) {
        inputs.forEach((input) => {
            input.required = required;
        });
    }

    function toggleGatewayFields() {
        const method = paymentMethod ? paymentMethod.value : '';
        const showCard = method === 'Card';
        const showBank = method === 'Bank Transfer';
        const showGcash = method === 'GCash';
        const showPaypal = method === 'PayPal';

        if (cardFields) {
            cardFields.classList.toggle('visible', showCard);
        }

        if (bankFields) {
            bankFields.classList.toggle('visible', showBank);
        }

        if (gcashFields) {
            gcashFields.classList.toggle('visible', showGcash);
        }

        if (paypalFields) {
            paypalFields.classList.toggle('visible', showPaypal);
        }

        setRequired(cardInputs, showCard);
        setRequired(bankInputs, showBank);
        setRequired(gcashInputs, showGcash);
        setRequired(paypalInputs, showPaypal);

        updateQrCodes();
    }

    function onlyDigits(value, maxLength) {
        return value.replace(/\D+/g, '').slice(0, maxLength);
    }

    function updateQrCodes() {
        const amount = amountInput && amountInput.value ? amountInput.value : '0';

        const gcashPayload = `GCASH|merchant=PawfectMatch Rescue|mobile=+639171234567|amount=${amount}|currency=PHP`;
        const paypalPayload = `https://paypal.me/pawfectmatch/${amount}`;

        if (gcashQr) {
            gcashQr.src = `https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=${encodeURIComponent(gcashPayload)}`;
        }

        if (paypalQr) {
            paypalQr.src = `https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=${encodeURIComponent(paypalPayload)}`;
        }
    }

    const cardNumberInput = document.getElementById('card_number');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', () => {
            const digits = onlyDigits(cardNumberInput.value, 16);
            cardNumberInput.value = digits.replace(/(.{4})/g, '$1 ').trim();
        });
    }

    const cardExpiryInput = document.getElementById('card_expiry');
    if (cardExpiryInput) {
        cardExpiryInput.addEventListener('input', () => {
            const digits = onlyDigits(cardExpiryInput.value, 4);
            if (digits.length <= 2) {
                cardExpiryInput.value = digits;
                return;
            }

            cardExpiryInput.value = digits.slice(0, 2) + '/' + digits.slice(2);
        });
    }

    const cardCvvInput = document.getElementById('card_cvv');
    if (cardCvvInput) {
        cardCvvInput.addEventListener('input', () => {
            cardCvvInput.value = onlyDigits(cardCvvInput.value, 4);
        });
    }

    const bankAccountInput = document.getElementById('bank_account_number');
    if (bankAccountInput) {
        bankAccountInput.addEventListener('input', () => {
            bankAccountInput.value = onlyDigits(bankAccountInput.value, 17);
        });
    }

    const bankRoutingInput = document.getElementById('bank_routing_number');
    if (bankRoutingInput) {
        bankRoutingInput.addEventListener('input', () => {
            bankRoutingInput.value = onlyDigits(bankRoutingInput.value, 9);
        });
    }

    const gcashMobileInput = document.getElementById('gcash_mobile');
    if (gcashMobileInput) {
        gcashMobileInput.addEventListener('input', () => {
            let value = gcashMobileInput.value.replace(/[^\d\+]/g, '');

            // Keep a single leading + if the user starts with international format.
            if (value.includes('+')) {
                value = (value.startsWith('+') ? '+' : '') + value.replace(/\+/g, '');
            }

            const hasPlus = value.startsWith('+');
            const digits = value.replace(/\D+/g, '').slice(0, 12);
            gcashMobileInput.value = hasPlus ? ('+' + digits) : digits;
        });
    }

    const gcashReferenceInput = document.getElementById('gcash_reference');
    if (gcashReferenceInput) {
        gcashReferenceInput.addEventListener('input', () => {
            gcashReferenceInput.value = gcashReferenceInput.value.toUpperCase().replace(/[^A-Z0-9-]/g, '').slice(0, 30);
        });
    }

    const paypalTransactionInput = document.getElementById('paypal_transaction_id');
    if (paypalTransactionInput) {
        paypalTransactionInput.addEventListener('input', () => {
            paypalTransactionInput.value = paypalTransactionInput.value.toUpperCase().replace(/[^A-Z0-9-]/g, '').slice(0, 30);
        });
    }

    if (amountInput) {
        amountInput.addEventListener('input', updateQrCodes);
    }

    if (paymentMethod) {
        paymentMethod.addEventListener('change', toggleGatewayFields);
    }

    updateQrCodes();
    toggleGatewayFields();
</script>
@endsection
