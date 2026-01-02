<div class="tab-pane fade show active" id="hosonhansu">
    <div class="card shadow-none border mb-0 radius-15">
        <div class="card-body">
            @include(
                'employee::components.info.persional-profile',
                compact('employee'))
            <hr>
            @include(
                'employee::components.info.dependent',
                compact('employee'))
            <hr>
            @include(
                'employee::components.info.bank-account',
                compact('employee'))
        </div>
    </div>
</div>
