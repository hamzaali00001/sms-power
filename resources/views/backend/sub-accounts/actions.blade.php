<a href="{{ route('sub-accounts.show', $user) }}" class="btn btn-success btn-xs" data-placement="top" data-toggle="tooltip" title="View User"><i class="fa fa-search"></i></a>
<a href="{{ route('sub-accounts.edit', $user) }}" class="btn btn-info btn-xs" data-placement="top" data-toggle="tooltip" title="Edit User"><i class="fa fa-pencil-square-o"></i></a>
@if (!$user->hasRole('admin'))
    <form action="{{ route('sub-accounts.destroy', $user) }}" class="form-delete" method="POST" style="display:inline">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <button type="submit" class="btn btn-danger btn-xs" data-placement="top" data-toggle="tooltip" title="Delete User"><i class="fa fa-trash-o"></i></button>
    </form>
@endif
