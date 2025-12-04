@if (session()->has('error'))
    <div class="row">
        <div class="col-9"></div>
            <div class="col-3">
                @if (session()->has('success'))
                    <div class="alert alert-success" role="alert" style="z-index:-1">
                        <strong>{{session()->get('success')}}</strong>
                    </div>
                @endif
                <div class="alert alert-warning alert-dismissible fade show" role="alert" style="z-index:-1">
                    <button class="close" data-dismiss="alert" data-delay="5000" type="button" aria-label="close">&times;</button>
                    <strong>{{session()->get('error')}}</strong>
                </div>
            </div>
        </div>
    </div>
@endif


