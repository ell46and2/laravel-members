<form 
    method="POST" 
    action="{{ route('jet.update', $jet) }}"
    enctype="multipart/form-data"
>
    @csrf
    @method('put')

    <div class="panel__header">
        <h2 class="panel__heading">
            Details
            <div class="text--color-base text--size-base font-weight-normal mt-1">
                Enter your personal details.
            </div>
        </h2>
    </div>

    <div class="panel__main flow-vertical--2">

        @include('form.partials._input', [
            'label' => 'First name',
            'field' => 'first_name',
            'type' => 'text',
            'attributes' => 'required',
            'errors' => $errors,
            'value' => $jet->first_name
        ])

        @include('form.partials._input', [
            'label' => 'Last name',
            'field' => 'last_name',
            'type' => 'text',
            'errors' => $errors,
            'attributes' => 'required',
            'value' => $jet->last_name
        ])

        @include('form.partials._input', [
            'label' => 'Middle name',
            'field' => 'middle_name',
            'type' => 'text',
            'errors' => $errors,
            'value' => $jet->middle_name
        ])

        @include('form.partials._input', [
            'label' => 'Address line 1',
            'field' => 'address_1',
            'type' => 'text',
            'attributes' => 'required',
            'errors' => $errors,
            'value' => $jet->address_1
        ])

        @include('form.partials._input', [
            'label' => 'Address line 2',
            'field' => 'address_2',
            'type' => 'text',
            'errors' => $errors,
            'value' => $jet->address_2
        ])

        <div class="form-group row">
            <label class="col-md-4 col-form-label text-md-right" for="country_id">Country</label>
            
            <div class="col-md-6">
                <country-select 
                    class="form-control"
                    resource="{{ json_encode($countries) }}"
                    :old="{{ old('country_id',$jet->country_id) }}"
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
                    country-id="{{ old('country_id',$jet->country_id) }}"
                    :old="{{ old('county_id',$jet->county_id) }}"
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
            'value' => $jet->postcode
        ])

        @include('form.partials._input', [
            'label' => 'Telephone',
            'field' => 'telephone',
            'type' => 'text',
            'attributes' => 'required',
            'errors' => $errors,
            'value' => $jet->telephone
        ])


        @include('form.partials._input', [
            'label' => 'Email Address',
            'field' => 'email',
            'type' => 'email',
            'attributes' => 'required',
            'errors' => $errors,
            'value' => $jet->email
        ])  
    </div>
    <button class="button button--primary button--block [ form-sticky-button ]" type="submit">Save</button>
</form>
    