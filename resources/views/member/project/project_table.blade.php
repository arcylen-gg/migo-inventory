<table class="table table-bordered table-striped table-condensed">
    <thead style="text-transform: uppercase">
        <tr>
            <th class="text-center">Project Name</th>
            <th class="text-center">Project Type</th>
            <th class="text-center">Project Owner</th>
            <th class="text-center">Project Company E-Mail</th>
            <th class="text-center">Project Contact</th>
            @if(count($_project)!=0)
            <th class="text-center"></th>
            @endif
        </tr>
    </thead>
    <tbody>
        @if(count($_project) == 0)
            <tr>
                <td class="text-center" colspan="5">NO DATA</td>
            </tr>
        @else
            @foreach($_project as $project)
            <tr project_id="{{ $project->project_id }}">
                <td class="text-center">{{ $project->project_name }}</td>
                <td class="text-center">{{ $project->project_type_name }}</td>
                <td class="text-center">{{ $project->project_email }}</td>
                <td class="text-center">{{ $project->project_email }}</td>
                <td class="text-center">{{ $project->project_contact }}</td>
                <td class="text-center">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-custom">
                            <li>
                                @if($project->archived==0)
                                <a class="action-archive" href="javascript:">Archive</a>
                                @else
                                <a class="action-archive" href="javascript:">Restore</a>
                                @endif
                            </li>
                            <li>
                                <a class="action-modify" href="javascript:">Modify</a>
                            </li>
                            <li>
                                <a class="action-view" href="javascript:">View</a>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>