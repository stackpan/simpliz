<x-layouts.manager>
    <x-slot name="breadcrumbs">
        <li>User</li>
    </x-slot>

    <div class="overflow-x-auto">
        <table class="table table-xs">
          <thead>
            <tr>
              <th></th>
              <th>Name</th>
              <th>Email</th>
              <th>Gender</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($users as $user)
            <tr>
              <th>{{ $loop->iteration }}</th>
              <td>{{ $user->name }}</td>
              <td>{{ $user->email }}</td>
              <td>{{ $user->gender->name }}</td>
              <td>
                <div class="join">
                    <button class="btn btn-xs btn-square btn-ghost join-item">
                        <x-heroicon-s-pencil-square class="w-5" />
                    </button>
                    <button class="btn btn-xs btn-square btn-ghost join-item">
                        <x-heroicon-s-trash class="w-5" />
                    </button>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th></th>
              <th>Name</th>
              <th>Email</th>
              <th>Gender</th>
              <th>Actions</th>
            </tr>
          </tfoot>
        </table>
      </div>
</x-layouts.manager>

