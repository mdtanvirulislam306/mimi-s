<div class="p-3">
    <!-- Name -->
    <div class="row">
        <div class="col-md-2 mt-md-2">
            <label>{{ translate('Name')}} <span class="text-danger">*</span></label>
        </div>
        <div class="col-md-10">
            <input class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Name')}}" rows="2" name="name" required></input>
        </div>
    </div>
    <!-- Phone -->
    <div class="row">
        <div class="col-md-2 mt-md-2">
            <label>{{ translate('Phone')}} <span class="text-danger">*</span></label>
        </div>
        <div class="col-md-10">
            <input type="tel" id="phone-code" class="form-control rounded-0" placeholder="" name="phone" autocomplete="off" required>
            <input type="hidden" name="country_code" value="">
        </div>
    </div> 
    <!-- District -->
    <div class="row mt-2">
        <div class="col-md-2 mt-md-2">
            <label>{{ translate('District')}} <span class="text-danger">*</span></label>
        </div>
        <div class="col-md-10">
            <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="state_id" required>
                <option value="">{{translate('Select District')}}</option>
                @foreach (\App\Models\State::get() as $state)
                    <option value="{{ $state->id }}">{{ $state->name }}</option>                
                @endforeach
            </select>
        </div>
    </div>

    <!-- Address -->
    <div class="row">
        <div class="col-md-2 mt-md-2">
            <label>{{ translate('Address')}} <span class="text-danger">*</span></label>
        </div>
        <div class="col-md-10 mt-md-2">
            <textarea class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Address')}}" rows="2" name="address" required></textarea>
        </div>
    </div>
</div>
