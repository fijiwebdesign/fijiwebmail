(function() {
  var e, t, s, i, r;
  $.support.transition || ($.fn.transition = $.fn.animate), i = window.location.protocol || document.location.protocol, "file:" === i && (Turbolinks.visit = function(e) {
    return window.location.href = e;
  }), e = $(document), r = function() {
    return $(".nano").nanoScroller({flash:!0, iOSNativeScrolling:!0});
  }, t = function(e) {
    var t, s, i, r;
    return s = $(e), r = s.attr("href"), t = $(r), i = s.data(), i.toggle = !1, t.offcanvas(i);
  }, s = function() {
    var e, s, i, o, n, a, l, u, c, p, k;
    return $("#oc-left-toggle").length && t("#oc-left-toggle"), $("#oc-right-toggle").length && t("#oc-right-toggle"), window.EmVars.colors = EmVars.colorsFromSass(), $(".datepick").datepicker(), $(".timepick").timepicker(), $(".datetimepick").datetimepicker(), $(".daterangepick").daterangepicker(), $(".superbox").SuperBox(), $("input.rating[type=number]").rating(), $("[rel=datepicker]").datepicker(), $("[rel=jvfloat]").jvFloat(), $("[rel=autosize]").autosize(), $("[rel=tabdrop]").tabdrop({text:"More"}), 
    $("[rel=editable]").editable(), $("[rel=classselector]").classselector(), $("[rel=panels]").panels(), $("[rel=tree]").tree(), $("[rel=summernote]").summernote(), $("[rel=selectize]").selectize({plugins:["remove_button"], dropdownClass:"selectize-dropdown animated fadeIn fast"}), $("[rel=selectize-tags]").selectize({delimiter:",", persist:!1, plugins:["remove_button"], dropdownClass:"selectize-dropdown animated fadeIn fast", create:function(e) {
      return{value:e, text:e};
    }}), o = $("[rel=icheck]"), o.iCheck({labelHover:!1, cursor:!0, inheritClass:!0}), a = $("[rel=powerange]"), a.each(function() {
      var e;
      return e = $(this), new Powerange(this, e.data());
    }), $("[rel=switch]").each(function() {
      var e, t;
      return e = $(this), t = new Switch(this), e.attr("readonly") || e.attr("disabled") ? void 0 : $(t.el).on("click", function(e) {
        return e.preventDefault(), t.toggle();
      });
    }), k = null, c = {tablet:1024, phone:480}, s = $(".datatable"), s.dataTable({sPaginationType:"bootstrap", bAutoWidth:!1, bStateSave:!1, fnPreDrawCallback:function() {
      return null != k ? k : k = new ResponsiveDatatablesHelper(s, c);
    }, fnRowCallback:function(e) {
      return k.createExpandIcon(e);
    }, fnDrawCallback:function() {
      return k.respond();
    }, fnInitComplete:function() {
      var e;
      return e = s.closest(".dataTables_wrapper"), e.find("select").selectize({dropdownClass:"selectize-dropdown animated fadeIn fast"}), e.find(".dataTables_filter input").addClass("form-control").attr("placeholder", "Search");
    }}), i = $("table[rel=floathead]"), i.floatThead({scrollContainer:function(e) {
      return e.closest(e.data("scroll"));
    }, useAbsolutePositioning:!1}), $("#oc-wrapper").on("statechange.bse.offcanvas", function() {
      var e;
      return e = function() {
        return $(window).trigger("resize"), i.floatThead("reflow");
      }, setTimeout(e, 360);
    }), e = $("#main-oc-container"), l = $("#main-oc-sidebar-left"), p = function() {
      var t, s;
      return s = l.outerHeight(!0), t = e.outerHeight(!0), e.css(s > t ? {height:s} : {height:"auto"});
    }, p(), l.find(".collapse").on("shown.bs.collapse hidden.bs.collapse", p), $("textarea[data-provide=markdown]").each(function() {
      var e;
      return e = $(this), e.data("markdown") ? void e.data("markdown").showEditor() : e.markdown(e.data());
    }), $(".nestable").nestable({group:"nestable", containerSelector:".dd-list", itemSelector:".dd-item", handle:".dd-handle", afterMove:function(e, t) {
      var s;
      return s !== t ? (s && s.el.removeClass("active"), t.el.addClass("active"), s = t) : void 0;
    }, onDrop:function(e, t, s) {
      return t.el.removeClass("active"), s(e);
    }}), $(".nav-select").navSelect(), $("a[href=#]").attr("data-no-turbolink", !0), r(), $(".current-month").text(moment().format("MMMM")), $(".current-day").text(moment().format("DD")), $("[data-ride=carousel]").on("slide.bs.carousel", function() {
      return setTimeout(function() {
        return $.sparkline_display_visible();
      }, 1);
    }), u = $("[data-tooltip-show]"), u.tooltip({trigger:"manual"}).tooltip("show"), n = $('[rel="popover-click"]'), n.popover({html:!0, content:function() {
      return $("#bigdrop").html();
    }, template:'<div class="popover popover-menu popover-grow-shrink" role="tooltip">\n  <div class="arrow"></div>\n  <h3 class="popover-title"></h3>\n  <div class="popover-content no-padding"></div>\n</div>'}), n.on("shown.bs.popover", function() {
      var e;
      return e = $(".popover-menu [rel=icheck]"), e.iCheck({labelHover:!1, cursor:!0, inheritClass:!0});
    });
  }, $(function() {
    return FastClick.attach(document.documentElement), s();
  }), e.on("shown.bs.modal", function() {
    return $(".modal-blur-content").css({polyfilter:"blur(3px)"});
  }), e.on("hidden.bs.modal", function() {
    return $(".modal-blur-content").css({polyfilter:"none"});
  }), e.on("page:load", function() {
    return Dropzone._autoDiscoverFunction(), $('[data-ride="carousel"]').each(function() {
      var e;
      return e = $(this), e.carousel(e.data());
    }), s();
  }), e.on("page:fetch", function() {
    return NProgress.start();
  }), e.on("page:change", function() {
    return NProgress.done();
  }), e.on("page:restore", function() {
    return NProgress.remove();
  }), $(window).resize(r), e.on("shown.bs.tab", r), e.on("shown.bs.tab", function() {
    return $(window).trigger("resize");
  }), e.on("shown.bs.tab", function() {
    var e;
    return e = function() {
      return $.sparkline_display_visible();
    }, setTimeout(e, 1);
  }), e.tooltip({selector:"[rel=tooltip]"}), e.popover({selector:'[rel="popover-sidebar"]', trigger:"hover", delay:{show:400, hide:0}}), e.popover({selector:"[rel=popover]", trigger:"hover"}), e.on("click", function(e) {
    var t;
    return t = $(e.target), "popover" !== t.data("toggle") && 0 === t.parents('[data-toggle="popover"]').length && 0 === t.parents(".popover.in").length ? $('[data-toggle="popover"]').popover("hide") : void 0;
  }), e.on("click", "#oc-open-chat", function() {
    //return $("#oc-right-toggle").tooltip("show"), $(".oc-scroll").animate({scrollTop:0}, "slow");
  }), e.on("shown.turbocard", ".turbo-placeholder", function() {
    return $(".oc-scroll").animate({scrollTop:0}, "slow");
  });
}).call(this);