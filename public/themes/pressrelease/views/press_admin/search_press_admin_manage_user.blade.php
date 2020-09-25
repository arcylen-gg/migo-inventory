
  <table  class="table table-bordered" id="showHere_table_user">
     <tr>
         <th style="width: 15%;">First Name</th>
         <th style="width: 15%;">Last Name</th>
         <th style="width: 15%;">Email</th>
         <th style="width: 15%;">Company Name</th>
         <th style="width: 20%;">Action</th>
     </tr>
      @foreach($_user as $_user_account)
        <tr>
           <td> <a href="">{{$_user_account->user_first_name}}</td>
           <td>{{$_user_account->user_last_name}}</td>
           <td>{{$_user_account->user_email}}</td>
           <td>{{$_user_account->user_company_name}}</td>
           <td>
            <a id="edit" href="/pressadmin/edit_user/{{$_user_account->user_id}}"><button type="button"  class="btn btn-warning center"><i class="fa fa-wrench" name="recipient_id" aria-hidden="true"></i>Edit</button></a>

            <a href="/pressadmin/delete_user/{{$_user_account->user_id}}"><button type="button" class="btn btn-danger center">
            <i class="fa fa-trash" name="recipient_id" aria-hidden="true"></i>Delete</button></a>

            <a href="/pressadmin/manage_force_login/{{$_user_account->user_id}}"><button type="button" class="btn btn-success center">
            <i class="fa fa-vcard-o" name="recipient_id" aria-hidden="true"></i>Force Login</button></a>
           </td>
        </tr>
        @endforeach
  </table>
              