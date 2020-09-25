<tr>
   <td class="text-center">
       <a href="#" class="edit-contact" data-content="{{$contact->contact_id}}" data-toggle="modal" data-target="#ContactModal">{{$contact->contact}}</a>
   </td>
   <td class="text-center">
       {{$contact->category}}
   </td>
   <td class="text-center">
       <input type="checkbox" class="checkboxDisplay" data-content="{{$contact->contact_id}}">
   </td>
</tr>