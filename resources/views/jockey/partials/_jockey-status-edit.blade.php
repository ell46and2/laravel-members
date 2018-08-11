@if($isAdmin)
	<div class="panel">
	    <div class="panel__inner">
	        <div class="panel__header">
	            <h2 class="panel__heading">
	                Status
	            </h2>
	        </div>
	        <div class="panel__main flow-vertical--3">
	        	<form 
					method="POST" 
					action="{{ route('jockey.status.update', $jockey) }}"
					class="[ js-confirmation ]"
				    data-confirm="Are you sure you want to change the status?"
				>
					{{ csrf_field() }}
					@method('put')
		            <div class="form-group flow-vertical--2">

		                <label for="" class="text--color-blue text--size-sm">Current Status</label>
		                <select class="form-control custom-select" name="status" id="status" required>
				           @foreach(['active', 'suspended', 'gone away', 'deleted'] as $status)
				                <option value="{{ $status }}" {{ old('status', $jockey->status) === $status ? 'selected' : '' }}>
				                    {{ ucfirst($status) }}
				                </option>
				           @endforeach
				        </select>
		                <button class="button button--primary button--block" type="submit">Update</button>

		            </div>
		        </form>
	        </div>
	    </div>
	</div>
@endif