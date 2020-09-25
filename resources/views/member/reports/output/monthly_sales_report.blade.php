<div class="main-container">
	<div class="report-container">
		<div class="panel panel-default panel-block panel-title-block panel-reportss load-data">
			<div class="panel-heading load-content">
				@include('member.reports.report_header_no_date')
				<div class="table-reponsive">
					<div class="double-scroll">
						<table class="table table-condensed collaptable table-bordered">
							<thead>
								<tr>
									<th class="text-center">Code</th>
									<th class="text-center">Name</th>
									@foreach($date_range as $daterange)
									<th class="text-center">{{$daterange}}</th>
									@endforeach
									<th class="text-center" nowrap>Client Total</th>
								</tr>
							</thead>
							<tbody>
								@if(count($customer) > 0)
									@foreach($customer as $customer_data)
									<tr>
										<td class="text-right">{{$customer_data->inv_customer_id}}</td>
									 	<td class="text-left" nowrap>{{ucfirst($customer_data->first_name.' '.$customer_data->middle_name.' '.$customer_data->last_name.' '.$customer_data->suffix_name)}}</td>
										@foreach($customer_data->total_date as $key => $customer_total_date)
										 	@if($customer_total_date != 0)
                                            	<td class="text-right">{{currency('',$customer_total_date)}}</td>
                                            @else
                                            	<td class="text-right"></td>
                                            @endif
										@endforeach
										@if($customer_data->total_all != 0)
											<td class="text-right">{{currency('',$customer_data->total_all)}}</td>
										@else
											<td class="text-center">-</td>
										@endif
									</tr>
									@endforeach
									<tr>
										<td colspan="2" class="text-center">TOTAL</td>
										@foreach($total_per_day as $per_day_total)
											@if($per_day_total != 0)
											<td class="text-right">{{currency('',$per_day_total)}}</td>
											@else
											<td class="text-right"></td>
											@endif
										@endforeach
										<td class="text-right">{{currency('',$total_month)}}</td>
									</tr>
								@else
								<tr>
									<td class="text-center" colspan="20">NO TRANSACTION YET</td>
								</tr>
								@endif
							</tbody>
						</table>
					</div>
					<h5 class="text-center">---- {{$now or ''}} ----</h5>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	.main-container
	{
		background-color: #fff;
		padding-top: 5px;
	}
	.title
	{
		margin-bottom: 20px;
	}
	.doubleScroll-scroll-wrapper
	{
		width: 100%;
	}
.double-scroll {
width: 100%;
}
</style>
<script type="text/javascript">
	
		/*
		* @name DoubleScroll
		* @desc displays scroll bar on top and on the bottom of the div
		* @requires jQuery
		*
		* @author Pawel Suwala - http://suwala.eu/
		* @author Antoine Vianey - http://www.astek.fr/
		* @version 0.5 (11-11-2015)
		*
		* Dual licensed under the MIT and GPL licenses:
		* https://www.opensource.org/licenses/mit-license.php
		* http://www.gnu.org/licenses/gpl.html
		*
		* Usage:
		* https://github.com/avianey/jqDoubleScroll
		*/
		(function( $ ) {
			
			jQuery.fn.doubleScroll = function(userOptions) {
			
				// Default options
				var options = {
					contentElement: undefined, // Widest element, if not specified first child element will be used
					scrollCss: {
						'overflow-x': 'auto',
						'overflow-y': 'hidden'
					},
					contentCss: {
						'overflow-x': 'auto',
						'overflow-y': 'hidden'
					},
					onlyIfScroll: true, // top scrollbar is not shown if the bottom one is not present
					resetOnWindowResize: false, // recompute the top ScrollBar requirements when the window is resized
					timeToWaitForResize: 30 // wait for the last update event (usefull when browser fire resize event constantly during ressing)
				};
			
				$.extend(true, options, userOptions);
			
				// do not modify
				// internal stuff
				$.extend(options, {
					topScrollBarMarkup: '<div class="doubleScroll-scroll-wrapper" style="height: 20px; width: 100%;"><div class="doubleScroll-scroll" style="height: 20px;"></div></div>',
					topScrollBarWrapperSelector: '.doubleScroll-scroll-wrapper',
					topScrollBarInnerSelector: '.doubleScroll-scroll'
				});
				var _showScrollBar = function($self, options) {
					if (options.onlyIfScroll && $self.get(0).scrollWidth <= $self.width()) {
						// content doesn't scroll
						// remove any existing occurrence...
						$self.prev(options.topScrollBarWrapperSelector).remove();
						return;
					}
				
					// add div that will act as an upper scroll only if not already added to the DOM
					var $topScrollBar = $self.prev(options.topScrollBarWrapperSelector);
					
					if ($topScrollBar.length == 0) {
						
						// creating the scrollbar
						// added before in the DOM
						$topScrollBar = $(options.topScrollBarMarkup);
						$self.before($topScrollBar);
						// apply the css
						$topScrollBar.css(options.scrollCss);
						$self.css(options.contentCss);
						// bind upper scroll to bottom scroll
						$topScrollBar.bind('scroll.doubleScroll', function() {
							$self.scrollLeft($topScrollBar.scrollLeft());
						});
						// bind bottom scroll to upper scroll
						var selfScrollHandler = function() {
							$topScrollBar.scrollLeft($self.scrollLeft());
						};
						$self.bind('scroll.doubleScroll', selfScrollHandler);
					}
						// find the content element (should be the widest one)
							var $contentElement;
					
					if (options.contentElement !== undefined && $self.find(options.contentElement).length !== 0) {
						$contentElement = $self.find(options.contentElement);
					} else {
						$contentElement = $self.find('>:first-child');
					}
					
					// set the width of the wrappers
					$(options.topScrollBarInnerSelector, $topScrollBar).width($contentElement.outerWidth());
					$topScrollBar.width($self.width());
					$topScrollBar.scrollLeft($self.scrollLeft());
					
				}
			
				return this.each(function() {
					
					var $self = $(this);
					
					_showScrollBar($self, options);
					
					// bind the resize handler
					// do it once
					if (options.resetOnWindowResize) {
					
						var id;
						var handler = function(e) {
							_showScrollBar($self, options);
						};
					
						$(window).bind('resize.doubleScroll', function() {
							// adding/removing/replacing the scrollbar might resize the window
							// so the resizing flag will avoid the infinite loop here...
							clearTimeout(id);
							id = setTimeout(handler, options.timeToWaitForResize);
						});
					}
				});
			}
		}( jQuery ));
		var monthly_sales_report_output = new monthly_sales_report_output();

		function monthly_sales_report_output()
		{
			init();

			function init()
			{
				load_scroll();
			}
			function load_scroll()
			{
				$('.double-scroll').doubleScroll();
				$('#sample2').doubleScroll({
				resetOnWindowResize: true
				});
			}
			this.load_scroll = function()
			{
				load_scroll();
			}
		}
</script>