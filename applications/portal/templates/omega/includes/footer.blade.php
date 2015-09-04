<footer id="footer" role="contentinfo">
    <section class="section swatch-black">
        <div class="container">
            <div class="row element-normal-top element-normal-bottom">
                <div class="col-md-10">
                    <p>
			RD-Switchboard is a new collaborative project by ANDS, CERN (European Organization for Nuclear Research), 
			Dryad and number of other international partners in the Research Data Alliance. This platform enables finding 
			connections between research datasets across national and international data registries.
                    </p>
                </div>
                <div class="col-md-2">
                    <a href="http://www.ands.org.au/" class="footer_logo"><img src="{{asset_url('images/footer_logo_rev.png', 'core')}}" alt="" style="height:75px;"/></a>    
                </div>
            </div>
    <!--    <div class="row element-normal-top element-normal-bottom">
        	<div class="col-md-3">
                    <div id="categories-4" class="sidebar-widget  widget_categories">
            	        <h3 class="sidebar-header">External Resources</h3>
                        <ul>
                    	    <li class="cat-item"> <a href="http://www.ands.org.au/" title="" target="_blank">ANDS Website</a> </li>
                            <li class="cat-item"> <a href="http://developers.ands.org.au" title="" target="_blank">Developers</a> </li>
                            <li class="cat-item"> <a href="{{base_url('registry/')}}" title="">ANDS Online Services</a> </li> 
                            @if(isset($ro) && $ro->core['id'])
                        	<li class="cat-item"> <a href="{{base_url('registry/registry_object/view/')}}/<?=$this->ro->id?>" title="">Registry View</a> </li>
                            @endif
                        </ul>
            	    </div>
                </div>
            </div> -->
        </div>
    </section>
</footer>