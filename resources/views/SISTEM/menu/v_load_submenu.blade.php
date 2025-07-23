@extends('template_machine.v_template_table')

@section('table')
<div data-aos="fade-zoom-in" data-aos-easing="ease-in-back" data-aos-delay="100" data-aos-offset="0">
<table id="DataTablesSub" class="table nowrap align-middle" style="width:100%">
    <thead>
        <tr>
            <th>no</th>
            <th>menu</th>
            <th>url</th>
            <th>aktif</th>
            <th>aksi</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>#1</td>
            <td>Tabungan</td>
            <td><span class="badge bg-info-subtle text-info">/tabungan</span></td>
            <td>
                <div class="form-check form-switch form-switch-md" dir="ltr">
                    <input type="checkbox" class="form-check-input" id="customSwitchsizemd">
                </div>
            </td>
            <td>
                <ul class="list-inline hstack gap-2 mb-0">
                    <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                        <a href="#showModal" data-bs-toggle="modal" class="text-primary d-inline-block edit-item-btn">
                            <i class="ri-pencil-fill fs-16"></i>
                        </a>
                    </li>
                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                        <a class="text-danger d-inline-block remove-item-btn" data-bs-toggle="modal" href="#deleteRecordModal">
                            <i class="ri-delete-bin-5-fill fs-16"></i>
                        </a>
                    </li>
                    
                </ul>
            </td>
        </tr>
    </tbody>
</table>
</div>
<script>
    $(document).ready(function() {
        $('#DataTablesSub').DataTable();
    });
</script>
@endsection