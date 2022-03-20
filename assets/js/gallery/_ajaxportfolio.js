import $ from 'jquery';
import * as scrollToTop from '../functions/scrollToTop';
import fitvids from '../app/fitvids';
import lightbox from '../app/lightbox';
import masonrythumbs from '../app/masonrythumbs';
import flexslider from './flexslider';

// module
function exec(){
	let $portfolioAjaxItems	= $('.portfolio-ajax').find('.portfolio-item'),
	$portfolioDetails = $('#portfolio-ajax-wrap'),
	$portfolioDetailsContainer = $('#portfolio-ajax-container'),
	$portfolioAjaxLoader = $('#portfolio-ajax-loader'),
	prevPostPortId = '';
}
export default { exec };

function portfolioAjaxloadInit(){
	if( $('.portfolio-ajax').length < 1 ){
		return;
	}

	$('.portfolio-ajax .portfolio-item a.portfolio-ajax-trigger').off( 'click' ).on( 'click', function(e) {
		let portPostId = $(this).parents('.portfolio-item').attr('id');
		if( !$(this).parents('.portfolio-item').hasClass('portfolio-active') ) {
			portfolioLoadItem(portPostId, prevPostPortId);
		}
		e.preventDefault();
	});
}

function portfolionewNextPrev(portPostId){
	let portNext = portfolioGetNextItem(portPostId);
	let portPrev = portfolioGetPrevItem(portPostId);
	$('#next-portfolio').attr('data-id', portNext);
	$('#prev-portfolio').attr('data-id', portPrev);
}

function portfolioLoadItem(portPostId, prevPostPortId, getIt){
	if(!getIt) { getIt = false; }
	let portNext = portfolioGetNextItem(portPostId);
	let portPrev = portfolioGetPrevItem(portPostId);
	if(getIt == false) {
		portfolioCloseItem();
		$portfolioAjaxLoader.fadeIn();
		let portfolioDataLoader = $('#' + portPostId).attr('data-loader');
		$portfolioDetailsContainer.load(portfolioDataLoader, { portid: portPostId, portnext: portNext, portprev: portPrev },
		function(){
			portfolioInitializeAjax(portPostId);
			portfolioOpenItem();
			$portfolioAjaxItems.removeClass('portfolio-active');
			$('#' + portPostId).addClass('portfolio-active');
		});
	}
};

function portfolioCloseItem(){
	if( $portfolioDetails && $portfolioDetails.height() > 32 ) {
		$portfolioAjaxLoader.fadeIn();
		$portfolioDetails.find('#portfolio-ajax-single').fadeOut('600', function(){
			$(this).remove();
		});
		$portfolioDetails.removeClass('portfolio-ajax-opened');
	}
};

function portfolioOpenItem(){
	let noOfImages = $portfolioDetails.find('img').length;
	let noLoaded = 0;

	if( noOfImages > 0 ) {
		$portfolioDetails.find('img').on('load', function(){
			noLoaded++;
			let topOffsetScroll = scrollToTop.topScrollOffset();
			if(noOfImages === noLoaded) {
				$portfolioDetailsContainer.css({ 'display': 'block' });
				$portfolioDetails.addClass('portfolio-ajax-opened');
				$portfolioAjaxLoader.fadeOut();
				setTimeout(function(){
					flexslider.exec();
					lightbox.exec();
					fitvids.exec();
					masonrythumbs.exec();
					$('html,body').stop(true).animate({
						'scrollTop': $portfolioDetails.offset().top - topOffsetScroll
					}, 900, 'easeOutQuad');
				},500);
			}
		});
	} else {
		let topOffsetScroll = scrollToTop.topScrollOffset();
		$portfolioDetailsContainer.css({ 'display': 'block' });
		$portfolioDetails.addClass('portfolio-ajax-opened');
		$portfolioAjaxLoader.fadeOut();
		setTimeout(function(){
			flexslider.exec();
			lightbox.exec();
			fitvids.exec();
			masonrythumbs.exec();
			$('html,body').stop(true).animate({
				'scrollTop': $portfolioDetails.offset().top - topOffsetScroll
			}, 900, 'easeOutQuad');
		},500);
	}
};

function portfolioGetNextItem(portPostId){
	let portNext = '';
	let hasNext = $('#' + portPostId).next();
	if(hasNext.length != 0) {
		portNext = hasNext.attr('id');
	}
	return portNext;
};

function portfolioGetPrevItem(portPostId){
	let portPrev = '';
	let hasPrev = $('#' + portPostId).prev();
	if(hasPrev.length != 0) {
		portPrev = hasPrev.attr('id');
	}
	return portPrev;
};

function portfolioInitializeAjax(portPostId){
	prevPostPortId = $('#' + portPostId);

	$('#next-portfolio, #prev-portfolio').off( 'click' ).on( 'click', function() {
		let portPostId = $(this).attr('data-id');
		$portfolioAjaxItems.removeClass('portfolio-active');
		$('#' + portPostId).addClass('portfolio-active');
		portfolioLoadItem(portPostId,prevPostPortId);
		return false;
	});

	$('#close-portfolio').off( 'click' ).on( 'click', function() {
		$portfolioDetailsContainer.fadeOut('600', function(){
			$portfolioDetails.find('#portfolio-ajax-single').remove();
		});
		$portfolioDetails.removeClass('portfolio-ajax-opened');
		$portfolioAjaxItems.removeClass('portfolio-active');
		return false;
	});
};

