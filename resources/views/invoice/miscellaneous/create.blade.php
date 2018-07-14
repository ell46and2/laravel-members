@extends('layouts.app')

@section('content')
<div class="container">
        
   
    <h2>Misc</h2>
    <form method="POST" action="{{ route('invoice.add-misc', $invoice) }}">
        {{ csrf_field() }}

            @include('form.partials._input', [
                'label' => 'Description',
                'field' => 'misc_name',
                'type' => 'text',
                'attributes' => 'required',
                'errors' => $errors
            ])

            
            <div class="form-group row">
                <div class="form-control{{ $errors->has('misc_date') ? ' is-invalid' : '' }}">
                    <datepicker-component name="misc_date" placeholder="Select Date" old="{{ old('misc_date') }}"></datepicker-component>
                </div>
                @if ($errors->has('misc_date'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('misc_date') }}</strong>
                    </span>
                @endif
            </div>

            @include('form.partials._input', [
                'label' => 'Amount',
                'field' => 'value',
                'type' => 'number',
                'attributes' => 'required',
                'errors' => $errors
            ])

             <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        Add to invoice
                    </button>
                </div>
            </div>

                    {{-- <td>
                        <datepicker-component name="misc_date" placeholder="Select Date" old="{{ old('misc_date') }}"></datepicker-component>
                    </td>
                    <td>
                        <input type="number" name="value">
                    </td> --}}
                  {{--   <td>
                        <button class="btn btn-primary" type="submit">Add</button>
                    </td>
                </tr>

            </tbody>
        </table> --}}
    </form>
    
    
</div>
@endsection
