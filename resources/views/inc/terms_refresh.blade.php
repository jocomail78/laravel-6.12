@include('inc.errors')
@if(session('updatedTerms'))
    <div class="alert alert-warning alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Warning!</strong>
        We have changed our Terms of services. You can find the latest version by clicking <a href="/terms/latest">here</a>.
        @if(session('acceptedId'))
            The one you have accepted can be found <a href="/terms/{{session('acceptedId')}}">here</a>.
        @else
            We can't identify the one you have accepted.
        @endif
        If you would like to accept our <a href="/terms/latest">latest Terms of services</a> please click <strong><a href="/users/accept-latest-terms">here</a></strong>.
    </div>
@endif

