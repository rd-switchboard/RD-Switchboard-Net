@extends('layouts/single')
@section('content')
<article>
    <script type="text/javascript">
    var oxyThemeData = {
        navbarHeight: 90,
        navbarScrolled: 70,
        navbarScrolledPoint: 200,
        navbarScrolledSwatches:
        {
            up: 'swatch-black',
            down: 'swatch-white'
        },
        scrollFinishedMessage: 'No more items to load.',
        hoverMenu:
        {
            hoverActive: false,
            hoverDelay: 1,
            hoverFadeDelay: 200
        }
    };
    </script>
    <section class="section swatch-black search-section section-text-shadow section-inner-shadow element-shorter-bottom" style="overflow:visible;z-index:9">
        @include('includes/banner-image')
        <div class="container">
            <div class="row">
                <div class="col-md-12 element-higher-top element-short-bottom">
                    <header class="text-center element-normal-bottom os-animation condensed animated fadeIn" data-os-animation="fadeIn" data-os-animation-delay="0s" style="-webkit-animation: 0s;">
                        <h1 class="bigger hairline bordered bordered-normal element-shorter-bottom">Research Data Switchboard</h1>
                        <p class="normal col-md-8 col-md-offset-2">
                            Find connections between research datasets across ANDS, Dryad, CERN and other international repositories
                        </p>
                    </header>
                </div>
                <div class="col-md-12 element-higher-bottom">
                    @include('includes/search-bar')
                </div>
            </div>
        </div>
    </section>
    <section class="section swatch-white element-short-bottom">
       <div class="container">
           <div class="row">
               <div class="col-md-12">
                   <header class="text-center element-short-top element-no-bottom not-condensed os-animation animated fadeInUp" data-os-animation="fadeInUp" data-os-animation-delay="0s" style="-webkit-animation: 0s;">
                       <h1 class="bigger hairline bordered bordered-normal os-animation animated fadeIn" data-os-animation="fadeIn" data-os-animation-delay="0s" style="-webkit-animation: 0s;"> Browse By Subjects </h1>
                   </header>
               </div>
           </div>
           <div class="row ">
            @include('includes/subjects-list')
           </div>
       </div>
    </section>

    <?php /*

    <section class="section swatch-white element-short-bottom">
       <div class="container">
           <div class="row">
               <div class="col-md-12">
                   <header class="text-center element-short-top element-medium-bottom not-condensed os-animation animated fadeInUp" data-os-animation="fadeInUp" data-os-animation-delay="0s" style="-webkit-animation: 0s;">
                       <h1 class="bigger hairline bordered bordered-normal os-animation animated fadeIn" data-os-animation="fadeIn" data-os-animation-delay="0s" style="-webkit-animation: 0s;"> Who Contributes to Research Data Switchboard </h1>
                       <h3><a href="{{portal_url('contributors')}}">View all</a></h3>
                   </header>
               </div>
           </div>
           <div class="row ">
            @include('includes/contributors-list')
           </div>
       </div>
    </section>
    */
    
    ?>
</article>
@stop