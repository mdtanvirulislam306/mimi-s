@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
		<div class="text-md-right">
			<a href="{{ route('branch.create') }}" class="btn btn-circle btn-info">
				<span>{{translate('Add New Branch')}}</span>
			</a>
		</div>
	</div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('branch')}}</h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th>{{translate('Name')}}</th>
                    <th>{{translate('Address')}}</th>
                    <th>{{translate('Phone')}}</th>
                    <th class="text-right" width="17%">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                @endphp
                @foreach ($branches as $key => $branch)
                    <tr>
                        <td>{{ ($key+1) + ($branches->currentPage() - 1)*$branches->perPage() }}</td>
                        <td>{{ $branch->name }}</td>
                        <td>{{ $branch->address }}</td>
                        <td>{{ $branch->phone }}</td>
                        <td class="text-right">
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('branch.edit', $branch->id)}}" title="{{ translate('Edit') }}">
                                <i class="las la-edit"></i>
                            </a>
                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('branch.destroy', $branch->id)}}" title="{{ translate('Delete') }}">
                                <i class="las la-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @php
                        $i++;
                    @endphp
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $branches->appends(request()->input())->links() }}
        </div>
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection


@section('script')
    <script type="text/javascript">
       
    </script>
@endsection
