<div class="card">
    <div class="card-header">Edit Profile</div>

    <div class="card-body">
        <form 
            method="POST" 
            action="{{ route('coach.update', $coach) }}"
            enctype="multipart/form-data"
        >
            @csrf
            @method('put')

            @include('form.partials._input', [
                'label' => 'First name',
                'field' => 'first_name',
                'type' => 'text',
                'attributes' => 'required',
                'errors' => $errors,
                'value' => $coach->first_name
            ])

            @include('form.partials._input', [
                'label' => 'Last name',
                'field' => 'last_name',
                'type' => 'text',
                'errors' => $errors,
                'attributes' => 'required',
                'value' => $coach->last_name
            ])

            @include('form.partials._input', [
                'label' => 'Middle name',
                'field' => 'middle_name',
                'type' => 'text',
                'errors' => $errors,
                'value' => $coach->middle_name
            ])

            @include('form.partials._input', [
                'label' => 'Alias',
                'field' => 'alias',
                'type' => 'text',
                'errors' => $errors,
                'value' => $coach->alias
            ])

            @include('form.partials._input', [
                'label' => 'Address line 1',
                'field' => 'address_1',
                'type' => 'text',
                'attributes' => 'required',
                'errors' => $errors,
                'value' => $coach->address_1
            ])

            @include('form.partials._input', [
                'label' => 'Address line 2',
                'field' => 'address_2',
                'type' => 'text',
                'errors' => $errors,
                'value' => $coach->address_2
            ])

            <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right" for="country_id">Country</label>
                
                <div class="col-md-6">
                    <country-select 
                        class="form-control"
                        resource="{{ json_encode($countries) }}"
                        :old="{{ old('country_id',$coach->country_id) }}"
                    ></country-select>

                    @if ($errors->has('country_id'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('country_id') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right" for="county_id">County</label>
                
                <div class="col-md-6">
                    <county-select 
                        class="form-control"
                        resource="{{ json_encode($counties) }}"
                        country-id="{{ old('country_id',$coach->country_id) }}"
                        :old="{{ old('county_id',$coach->county_id) }}"
                    ></county-select>

                    @if ($errors->has('county_id'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('county_id') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            @include('form.partials._input', [
                'label' => 'Post code',
                'field' => 'postcode',
                'type' => 'text',
                'attributes' => 'required',
                'errors' => $errors,
                'value' => $coach->postcode
            ])

            @include('form.partials._input', [
                'label' => 'Telephone',
                'field' => 'telephone',
                'type' => 'text',
                'attributes' => 'required',
                'errors' => $errors,
                'value' => $coach->telephone
            ])

            @include('form.partials._input', [
                'label' => 'Twitter handle',
                'field' => 'twitter_handle',
                'type' => 'text',
                'errors' => $errors,
                'value' => $coach->twitter_handle
            ])

            @include('form.partials._input', [
                'label' => 'Email Address',
                'field' => 'email',
                'type' => 'email',
                'attributes' => 'required',
                'errors' => $errors,
                'value' => $coach->email
            ])

            @include('form.partials._input', [
                'label' => 'VAT number',
                'field' => 'vat_number',
                'type' => 'text',
                'errors' => $errors,
                'value' => $coach->vat_number
            ])

            <div class="form-group row">
                <label for="bio" class="col-md-4 col-form-label text-md-right">Bio</label>

                <div class="col-md-6">
                    <textarea
                        id="bio"
                        name="bio"
                        class="form-control"
                    >{{ old('bio', $coach->bio) }}
                    </textarea>
                        
                    @if ($errors->has('bio'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('bio') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        Update
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>