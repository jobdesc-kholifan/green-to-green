<?php

/**
 * @var \App\Helpers\Collections\Orders\OrderCollection $order
 * */

?>
<form>
    {{ csrf_field() }}
    <div class="modal-header">
        <h3 class="card-title">Detail {{ $title }}</h3>
        <span class="close" data-dismiss="modal">&times;</span>
    </div>
    <div class="modal-body">
        <dl class="row dt-bold">
            <dt class="col-sm-4">Nama Lengkap</dt>
            <dd class="col-sm-8">{{ $order->getUser()->getFullName() }}</dd>
            <dt class="col-sm-4">Driver Note</dt>
            <dd class="col-sm-8">{{ $order->getDriverNote() }}</dd>
            <dt class="col-sm-4">Address</dt>
            <dd class="col-sm-8">{{ $order->getAddress() }}</dd>
            <dd class="col-sm-12">
                <embed src="https://maps.google.com/maps?q={{ $order->getLanLng() }}&hl=es;z=14&amp;output=embed" style="width: 100%;height: 200px">
            </dd>
            <dt class="col-sm-4 mb-0">Status</dt>
            <dd class="col-sm-8 mb-0">{{ $order->getStatus()->getName() }}</dd>
            @if($order->getScheduleId() != null)
                <dt class="col-sm-4 text-sm mb-0">Pengambilan </dt>
                <dd class="col-sm-8 text-sm mb-0">{{ $order->getSchedule()->getDate('d F Y') }} - {{ $order->getSchedule()->getStaff()->getFullName() }} ({{ $order->getSchedule()->getStaff()->getPhoneNumber() }})</dd>
                <dt class="col-sm-4 text-sm mb-0"></dt>
                <dd class="col-sm-8 text-sm mb-0">{{ $order->getSchedule()->getDesc() }}</dd>
            @endif
        </dl>
    </div>
</form>
