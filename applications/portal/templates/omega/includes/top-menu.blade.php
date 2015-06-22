<div class="navbar swatch-black" role="banner">
    <div class="container" style="z-index:10">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".main-navbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div>
                <a href="{{portal_url()}}" class="navbar-brand">
                    <span>RD-Switchboard</span> Browser
                </a>
            </div>
            
            @if(current_url()!=base_url())
            <div class="clear"><small>Research Data Switchboard</small></div>
            @endif
        </div>
        <nav class="collapse navbar-collapse main-navbar" role="navigation">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="{{portal_url('page/about')}}">About</a></li>
                <?php 
                    $profile_image = profile_image();
                ?>
                @if($profile_image)
                   <li><a href="{{portal_url('profile')}}"><img src="{{ $profile_image }}" alt="" class="profile_image_small"></a></li>
                @endif
            </ul>
        </nav>
    </div>
</div>
<button class="yellow_button feedback_button">Feedback</button>
<button class="yellow_button help_button" data-toggle="modal" data-target="#help_modal"><i class="fa fa-question-circle"></i> Help</button>
@include('includes/help-modal')