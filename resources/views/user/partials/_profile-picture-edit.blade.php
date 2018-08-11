<div class="panel">
    <div class="panel__inner">
        <div class="panel__header">
            <h2 class="panel__heading">
                Profile Picture
                <div class="text--color-base text--size-base font-weight-normal mt-1">
                    Upload a profile picture of yourself in the following file formats, JPG or PNG.
                </div>
            </h2>
        </div>

        <div class="panel__main">
            <div class="w-25 text-center">
                <div class="[ avatar avatar--green ]">
                    <div class="avatar__image" style="background-image:url({{ $user->getAvatar() }});"></div>
                </div>
                <a class="link--underlined" href="">Remove</a>
            </div>
        </div>

        <a class="panel__call-to-action" href="">Upload</a>
    </div>
</div>



{{-- <div>
	<h3>Profile Picture</h3>
	<div class="[ profile-summary__avatar ] [ avatar avatar--red ]">
        <div class="avatar__image" style="background-image:url({{ $user->getAvatar() }});"></div>
    </div>
</div> --}}