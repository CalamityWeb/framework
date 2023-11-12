<?php
/**
 * @var $this \tframe\core\View
 */

use tframe\common\components\button\Button;
use tframe\common\components\text\Text;
use tframe\core\Application;

$this->title = 'Users';
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <?= Button::generateClickButton('/users/create', 'btn-primary', 'New User', 'fa-plus') ?>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover table-striped dataTable dtr-inline" id="dataTable">

                </table>
            </div>
        </div>
    </div>
</div>

<?php

$notset = Text::notSetText();

$this->registerJS(<<<JS

$("#dataTable").DataTable({
    "paging": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "responsive": true,
    "dom": "QB<\"row justify-content-between mt-3\"<\"col-auto\"l><\"col-auto\"f>>rtip",
    "buttons": [
        "copyHtml5", "excelHtml5", "pdfHtml5", "print"
    ],
    "processing": true,
    ajax: {
        url: '/api/users/list',
        dataSrc:""
    },
    columns: [
        { title:"ID", data: 'id' },
        { title:"Email", data: 'email' },
        { title:"Name", data: function (data) { return data.firstName + ' ' + data.lastName } },
        { title:"Email confirmed", data:  function (data) { return (data.email_confirmed) ? '<i class="fa-solid fa-circle-check text-success"></i>' : 
        '<i class="fa-solid fa-circle-xmark text-danger"></i>' } },
        { title:"Created at", data:  'created_at' },
        { title:"Updated at", data:  function (data) { return (!data.updated_at) ? '$notset' : data.updated_at } },
        { title:'<i class="fa-solid fa-gear"></i>', data: function (data) { return 'edit' } }
    ],
});

JS);

?>