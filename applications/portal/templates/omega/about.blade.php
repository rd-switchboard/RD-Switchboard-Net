@extends('layouts/single')
@section('content')
<?php
$banner = isset($banner) ? $banner : asset_url('images/collection_banner.jpg', 'core');
?>
<article>
    <section class="section swatch-black section-text-shadow section-inner-shadow" style="overflow:visible;z-index:9">
        @include('includes/banner-image')
    <div class="container">
        <div class="row">
            <div class="col-md-12 element-higher-top element-short-bottom">
                <header class="text-center element-normal-bottom os-animation condensed animated fadeIn" data-os-animation="fadeIn" data-os-animation-delay="0s" style="-webkit-animation: 0s;">
                    <h1 class="bigger hairline bordered bordered-normal element-shorter-bottom">About Research Data Switchboard</h1>

                </header>
            </div>
            </div>
        </div>
        </section>
        <section class="section swatch-white">
        <div class="container-fluid" style="padding:0px">

                <div class="col-md-12  swatch-gray">
                    <div class="col-md-2 not-condensed os-animation animated fadeInUp swatch-gray"> </div>
                    <div class="col-md-8 not-condensed os-animation animated fadeInUp swatch-gray" style="-webkit-animation: 0s;padding-top:30px;padding-bottom:50px">
                        <div class="text-center" style="-webkit-animation: 0s;">
                            <h1 class="bigger hairline bordered bordered-normal os-animation animated fadeIn" data-os-animation="fadeIn" data-os-animation-delay="0s" style="-webkit-animation: 0s;color:#000000">

                                An Open Collaborative Software Project</h1>
                        </div>
                        <div style="padding-left:50px;padding-right:50px">
                            <p>
Research Data Switchboard (RD-Switchboard) is an open and collaborative software solution initiated by ANDS as part of a working group of the Research Data Alliance. The working group has input from:
<ul>
	<li>Australian National Data Service (ANDS) (Australia)</li>
	<li>Dryad (US)</li>
	<li>CERN InspireHEP (Switzerland)</li>
	<li>figshare (UK)</li>
	<li>da|ra and GESIS (Germany)</li>
	<li>Data Curation Unit (Greece)</li></li>
	<li>OpenAIRE (European Infrastructure)</li>
</ul>

<p>
RD-Switchboard project addresses the problem of cross platform discovery by connecting datasets together across multiple registries on the basis of co-authorship or other collaboration models such as joint funding and grants. The best metaphor for these services is the SEE ALSO section in online bookstores, where customers are invited to look at other products by the same author, related topics or similar publishers. 
</p>
<p>
This website (www.rd-switchboard.net) is a demonstrator of this capability using data collections in Research Data Australia, Dryad and CERN InspireHEP. In addition, it shows how <a href="https://github.com/rd-switchboard/Widgets">RD-Switchboard Widget and API</a> can be integrated into research data discovery platforms.
</p>
                            </p>
                        </div>
                    </div>
                </div>
            </div>



        <div class="col-md-12 swatch-black" style="background-image: url({{$banner}}); background-size: cover; background-repeat: no-repeat;" class="background-media skrollable skrollable-between">
            <div class="col-md-2"> </div>
            <div class="col-md-8 not-condensed os-animation animated fadeInUp" style="-webkit-animation: 0s;padding-top:30px;padding-bottom:50px">
                <div class="col-md-6 text-center not-condensed os-animation animated fadeInUp">
                    <div class="counter bordered text-default element-short-top element-short-bottom os-animation" data-count="{{number_format($collections)}}" data-format="(,ddd)" data-os-animation="fadeIn" data-os-animation-delay="0s">
                        <span class="value super hairline odometer-counter data-os-animation-delay" style="color:#ffffff"></span>
                    </div><h1 style="color:#ffffff">Datasets</h1>
                </div>
                <div class="col-md-6 text-center">
                    <div class="counter bordered text-default element-short-top element-short-bottom os-animation" data-count="{{count($contributors)}}" data-format="(,ddd)" data-os-animation="fadeIn" data-os-animation-delay="0s" style="color:#ffffff">
                        <span class="value super hairline odometer-counter data-os-animation-delay" style="color:#ffffff"></span>
                    </div>
                    <h1 style="color:#ffffff">Contributors</h1>

                 </div>
            </div>
            <div class="col-md-2"> </div>
        </div>


    </section>
</article>
@stop