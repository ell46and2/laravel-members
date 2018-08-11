@if($currentUser->id === $user->id)
	<div class="panel">
	    <div class="panel__inner">
	        <div class="panel__header">
	            <h2 class="panel__heading">
	                Password
	                <div class="text--color-base text--size-base font-weight-normal mt-1">
	                    Control your login, password and notification settings.
	                </div>
	            </h2>
	        </div>
	        <div class="panel__main flow-vertical--3">
	            <div class="form-group flow-vertical--2">

	            	<form action="{{ route('profile.password.update') }}" method="POST">
	            		{{ csrf_field() }}
	            		@method('put')

						<label for="" class="text--color-blue text--size-sm">Current Password</label>
		            	@include('form.partials._input', [
	                        'field' => 'old_password',
	                        'type' => 'password',
	                        'errors' => $errors,
	                        'placeholder' => 'Current password...',
	                        'attributes' => 'required',
	                    ])

	                    <label for="" class="text--color-blue text--size-sm">New Password</label>
		            	@include('form.partials._input', [
	                        'field' => 'password',
	                        'type' => 'password',
	                        'errors' => $errors,
	                        'placeholder' => 'New password...',
	                        'attributes' => 'required',
	                    ])

	                    <label for="" class="text--color-blue text--size-sm">Confirm Password</label>
		            	@include('form.partials._input', [
	                        'field' => 'password_confirmation',
	                        'type' => 'password',
	                        'errors' => $errors,
	                        'placeholder' => 'Confirm password...',
	                        'attributes' => 'required',
	                    ])

		                <button class="button button--primary button--block">Update</button>
		            </form>
	            </div>
	        </div>
	    </div>
	</div>
@endif