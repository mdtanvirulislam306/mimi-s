<div class="form-group">
    <div class="row">
        <label class="col-sm-2 control-label" for="name">{{translate('Name')}} <span class="text-danger">*</span></label>
        <div class="col-sm-10">
            <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" class="form-control" required>
        </div>
    </div>
</div>
<div class="form-group">
    <div class=" row">
        <label class="col-sm-2 control-label" for="phone">{{translate('Phone')}} <span class="text-danger">*</span></label>
        <div class="col-sm-10">
            <input type="number" min="0" placeholder="{{translate('Phone')}}" id="phone" name="phone" class="form-control" required>
        </div>
    </div>
</div>
<div class="form-group">
    <div class=" row">
        <label class="col-sm-2 control-label" for="address">{{translate('Address')}}</label>
        <div class="col-sm-10">
            <textarea placeholder="{{translate('Address')}}" id="address" name="address" class="form-control" ></textarea>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-sm-2 control-label">
            <label>{{ translate('District')}} </label>
        </div>
        <div class="col-sm-10">
            <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="state_id" >
                <option value="">{{ translate('Select your district') }}</option>
                @foreach ($state as $key => $district)
                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-2">
            <label>{{ translate('City')}}</label>
        </div>
        <div class="col-sm-10">
            <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="city_id" required>

            </select>
        </div>
    </div>
</div>
