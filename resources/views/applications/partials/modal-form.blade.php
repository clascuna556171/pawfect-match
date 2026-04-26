<form id="modal-application-form" action="{{ route('applications.store') }}" method="POST" class="modal-application-form">
    @csrf
    <input type="hidden" name="pet_id" value="{{ $pet->id }}">

    <div id="modal-form-errors" style="display:none;" role="alert" aria-live="assertive"></div>

    <div class="modal-application-grid">
        <div class="field">
            <label for="modal_home_type"><i class="fa-solid fa-house"></i> Type of home</label>
            <select id="modal_home_type" name="home_type" required>
                <option value="">Select your home type</option>
                @foreach(['House', 'Apartment', 'Condo', 'Townhouse', 'Farm', 'Other'] as $homeType)
                    <option value="{{ $homeType }}">{{ $homeType }}</option>
                @endforeach
            </select>
        </div>

        <div class="field">
            <label for="modal_household_members"><i class="fa-solid fa-users"></i> Household members</label>
            <input id="modal_household_members" type="number" name="household_members" min="1" max="30" required placeholder="E.g. 2">
        </div>

        <div class="field full">
            <label><i class="fa-solid fa-tree"></i> Yard available?</label>
            <div class="radio-set custom-radio-cards">
                <label class="radio-card"><input type="radio" name="yard_available" value="1" required> <span class="radio-content">Yes</span></label>
                <label class="radio-card"><input type="radio" name="yard_available" value="0" required> <span class="radio-content">No</span></label>
            </div>
        </div>

        <div class="field full">
            <label><i class="fa-solid fa-paw"></i> Do you have other pets?</label>
            <div class="radio-set custom-radio-cards">
                <label class="radio-card"><input type="radio" name="has_other_pets" value="1" required> <span class="radio-content">Yes</span></label>
                <label class="radio-card"><input type="radio" name="has_other_pets" value="0" required> <span class="radio-content">No</span></label>
            </div>
        </div>

        <div class="field full">
            <label for="modal_other_pets_details"><i class="fa-solid fa-circle-info"></i> If yes, describe them</label>
            <textarea id="modal_other_pets_details" name="other_pets_details" placeholder="Species, age, temperament, and compatibility notes"></textarea>
        </div>

        <div class="field full">
            <label for="modal_experience_with_pets"><i class="fa-solid fa-star"></i> Your experience with pets</label>
            <textarea id="modal_experience_with_pets" name="experience_with_pets" placeholder="Have you owned pets before? Please describe." required></textarea>
        </div>

        <div class="field full">
            <label for="modal_employment_sustainability"><i class="fa-solid fa-briefcase"></i> Employment & Financial Sustainability</label>
            <textarea id="modal_employment_sustainability" name="employment_sustainability" placeholder="Discuss your current employment or ways you plan to financially sustain the needs of the pet." required></textarea>
        </div>

        <div class="field full">
            <label for="modal_reason_for_adoption"><i class="fa-solid fa-heart"></i> Reason for adoption</label>
            <textarea id="modal_reason_for_adoption" name="reason_for_adoption" placeholder="Why would you like to adopt this pet?" required></textarea>
        </div>

        <div class="field full">
            <label for="modal_references"><i class="fa-regular fa-address-book"></i> References (optional)</label>
            <textarea id="modal_references" name="references" placeholder="Veterinarian or personal references"></textarea>
        </div>

        <div class="field full">
            <label for="modal_additional_information"><i class="fa-regular fa-message"></i> Additional information (optional)</label>
            <textarea id="modal_additional_information" name="additional_information"></textarea>
        </div>
    </div>

    <div class="modal-actions">
        <button type="button" class="btn-outline" id="modal-cancel-btn">Cancel</button>
        <button type="submit" class="btn-primary" id="modal-submit-btn">Submit Application</button>
    </div>
</form>
