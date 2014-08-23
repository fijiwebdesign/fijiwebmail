function setupJqUi() {
  function e() {
    var e = s.val() || "Tab " + o, t = "tabs-" + o, a = $(r.replace(/#\{href\}/g, "#" + t).replace(/#\{label\}/g, e)), l = i.val() || "Tab " + o + " content.";
    n.find(".ui-tabs-nav").append(a), n.append("<div id='" + t + "'><p>" + l + "</p></div>"), n.tabs("refresh"), o++;
  }
  function t() {
    var e = p.progressbar("value") || 0;
    p.progressbar("value", e + 1), 99 > e && setTimeout(t, 100);
  }
  $("#menu-collapse").accordion({header:"h3"}), $("#btn-dialog-simple").click(function() {
    return $("#modal-simple").dialog("open"), !1;
  }), $("#modal-simple").dialog({autoOpen:!1, buttons:[{text:"Close", click:function() {
    $(this).dialog("close");
  }, "class":"btn ui-button-inverse"}]}), $("#btn-dialog-message").click(function() {
    return $("#modal-message").dialog("open"), !1;
  }), $("#modal-message").dialog({autoOpen:!1, modal:!0, buttons:[{text:"Close", click:function() {
    $(this).dialog("close");
  }, "class":"btn ui-button-inverse"}]}), $("#btn-dialog-button").click(function() {
    return $("#modal-button").dialog("open"), !1;
  }), $("#modal-button").dialog({autoOpen:!1, width:600, buttons:[{text:"Delete", click:function() {
  }, "class":"btn btn-danger ui-button-danger"}, {text:"Edit", click:function() {
  }, "class":"btn btn-warning ui-button-warning"}, {text:"Other", click:function() {
  }, "class":"btn btn-inverse ui-button-inverse"}, {text:"Close", click:function() {
    $(this).dialog("close");
  }, "class":"btn ui-button-inverse"}]}), $("#btn-dialog-simple, #btn-dialog-message, #btn-dialog-button, ul#icons li").hover(function() {
    $(this).addClass("ui-state-hover");
  }, function() {
    $(this).removeClass("ui-state-hover");
  }), $(".ui-dialog :button").blur(), $("#tabs2, #tabs, #tabs3").tabs();
  var s = $("#tab_title"), i = $("#tab_content"), r = "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close'>Remove Tab</span></li>", o = 2, n = $("#tabs2").tabs(), a = $("#dialog2").dialog({autoOpen:!1, modal:!0, buttons:{Add:function() {
    e(), $(this).dialog("close");
  }, Cancel:function() {
    $(this).dialog("close");
  }}, close:function() {
    l[0].reset();
  }}), l = a.find("form").submit(function(t) {
    e(), a.dialog("close"), t.preventDefault();
  });
  $("#add_tab").button().click(function() {
    a.dialog("open");
  }), $("#tabs2").on("click", "span.ui-icon-close", function() {
    var e = $(this).closest("li").remove().attr("aria-controls");
    $("#" + e).remove(), n.tabs("refresh");
  }), $("#sampleButton").on("click", function(e) {
    e.preventDefault(), $("#modal-simple").dialog({autoOpen:!0, modal:!0, width:600, buttons:{Save:function() {
      $(this).dialog("close");
    }, Cancel:function() {
      $(this).dialog("close");
    }}});
  }), $("#datepicker").datepicker({inline:!0}), $("#h-slider").slider({range:!0, values:[17, 67]}), $("#v-slider").slider({orientation:"vertical", range:"min", min:0, max:100, value:60, slide:function(e, t) {
    $("#amount").val(t.value);
  }}), $("#amount").val($("#v-slider").slider("value"));
  var u = ["ActionScript", "AppleScript", "Asp", "BASIC", "C", "C++", "Clojure", "COBOL", "ColdFusion", "Erlang", "Fortran", "Groovy", "Haskell", "Java", "JavaScript", "Lisp", "Perl", "PHP", "Python", "Ruby", "Scala", "Scheme"];
  $("#tags-autocomplete").autocomplete({source:u, appendTo:"#autocomplete"}), $("#menu").menu();
  var c = $("#spinner").spinner();
  $("#disable").click(function() {
    c.spinner(c.spinner("option", "disabled") ? "enable" : "disable");
  }), $("#destroy").click(function() {
    c.data("ui-spinner") ? c.spinner("destroy") : c.spinner();
  }), $("#getvalue").click(function() {
    alert(c.spinner("value"));
  }), $("#setvalue").click(function() {
    c.spinner("value", 5);
  }), $("#tooltip").tooltip(), $("#tooltip-top").tooltip({position:{my:"center bottom-15", at:"center top", using:function(e, t) {
    $(this).css(e), $("<div>").addClass("arrow bottom").addClass(t.vertical).addClass(t.horizontal).appendTo(this);
  }}}), $("#tooltip-right").tooltip({position:{my:"left+15 left", at:"right center", using:function(e, t) {
    $(this).css(e), $("<div>").addClass("arrow left").addClass(t.vertical).addClass(t.horizontal).appendTo(this);
  }}}), $("#tooltip-left").tooltip({position:{my:"right-15 center", at:"left center", using:function(e, t) {
    $(this).css(e), $("<div>").addClass("arrow right").addClass(t.vertical).addClass(t.horizontal).appendTo(this);
  }}}), $("#tooltip-bottom").tooltip({position:{my:"center top+15", at:"center bottom", using:function(e, t) {
    $(this).css(e), $("<div>").addClass("arrow top").addClass(t.vertical).addClass(t.horizontal).appendTo(this);
  }}}), $("#progressbar").progressbar({value:37});
  var p = $("#custom-progressbar"), d = $(".progress-label");
  p.progressbar({value:!1, change:function() {
    d.text(p.progressbar("value") + "%");
  }, complete:function() {
    d.text("Complete!");
  }}), setTimeout(t, 3E3);
  var h = $(window), k = $(document.body), m = $(".navbar").outerHeight(!0) + 10;
  k.scrollspy({target:".bs-sidebar", offset:m}), h.on("load", function() {
    k.scrollspy("refresh");
  }), $("section [href^=#]").click(function(e) {
    e.preventDefault();
  }), setTimeout(function() {
    var e = $(".bs-sidebar");
    e.affix({offset:{top:function() {
      var t = e.offset().top, s = parseInt(e.children(0).css("margin-top"), 10), i = $(".bs-docs-nav").height();
      return this.top = t - i - s;
    }, bottom:function() {
      return this.bottom = $(".bs-footer").outerHeight(!0);
    }}});
  }, 100), setTimeout(function() {
    $(".bs-top").affix();
  }, 100), $(".download-btn").button(), window.prettyPrint && prettyPrint(), $("a.targetblank").on("click", function() {
    return window.open($(this).attr("href")), !1;
  });
}
(function() {
  $(document).on("ready page:load", function() {
    return $(".table-checkable").on("ifChecked", function() {
      return $(this).closest("table").find(".table-checkable-row").iCheck("check");
    }), $(".table-checkable").on("ifUnchecked", function() {
      return $(this).closest("table").find(".table-checkable-row").iCheck("uncheck");
    });
  });
}).call(this), function() {
  $(document).on("ready page:load", function() {
    var e;
    return e = $(".reportrange"), e.each(function() {
      var e, t, s;
      return e = $(this), t = e.find("span"), s = "MMMM D", e.daterangepicker({startDate:moment().subtract("days", 29), endDate:moment(), minDate:"01/01/2014", maxDate:"12/31/2014", dateLimit:{days:60}, timePicker:!1, timePickerIncrement:1, timePicker12Hour:!0, ranges:{Today:[moment(), moment()], Yesterday:[moment().subtract("days", 1), moment().subtract("days", 1)], "Last 7 Days":[moment().subtract("days", 6), moment()], "This Month":[moment().startOf("month"), moment().endOf("month")], "Last Month":[moment().subtract("month", 
      1).startOf("month"), moment().subtract("month", 1).endOf("month")]}, opens:"left", buttonClasses:["btn-sm"], applyClass:"btn btn-success", cancelClass:"btn btn-default", format:"MM/DD/YYYY", separator:" to ", locale:{fromLabel:"From", toLabel:"To", customRangeLabel:"Custom Range", daysOfWeek:EmVars.helpers.abbrDays, monthNames:EmVars.helpers.monthNames, firstDay:1}}, function(e, i) {
        t.html(e.format(s) + " - " + i.format(s));
      }), t.html(moment().subtract("days", 29).format(s) + " - " + moment().format(s));
    });
  });
}.call(this), function() {
  var e;
  e = [{title:"All Day Event", start:"2014-06-01", className:"bg-primary-lt"}, {title:"Another All Day Event", start:"2014-06-02", className:"bg-primary-lt"}, {title:"Long Event", start:"2014-06-07", end:"2014-06-10", className:"bg-info-dk"}, {id:999, title:"Repeating Event", start:"2014-06-09T16:00:00", className:"bg-success"}, {id:999, title:"Repeating Event", start:"2014-06-16T16:00:00", className:"bg-warning"}, {title:"Meeting", start:"2014-06-12T10:30:00", end:"2014-06-12T12:30:00", className:"bg-danger"}, 
  {title:"Lunch", start:"2014-06-12T12:00:00", className:"bg-primary-lt"}, {title:"Birthday Party", start:"2014-06-13T07:00:00", className:"bg-primary-lt"}, {title:"Click for Google", url:"http://google.com/", start:"2014-06-28", className:"bg-light"}, {title:"Extra event", url:"http://google.com/", start:"2014-06-29", className:"bg-primary-lt"}], $(document).on("ready page:load", function() {
    return $("[rel=fullcalendar]").each(function() {
      var t;
      return t = $(this), t.fullCalendar({header:{left:"prev,next today", center:"title", right:"month,agendaWeek,agendaDay"}, events:e, defaultDate:"2014-06-12", editable:!0, dragOpacity:"0.5", selectable:!0, selectHelper:!0, select:function(e, s) {
        var i;
        return i = {title:"New event", start:e, end:s}, t.fullCalendar("renderEvent", i, !0);
      }, eventClick:function(e) {
        var t, s, i;
        return t = $("#modal"), s = e.className[0].match(/^bg-(.*)$/)[1], i = "modal-" + s, t.addClass(i), t.find(".modal-title").html(e.title), t.modal("show"), t.one("hidden.bs.modal", function() {
          return t.removeClass(i);
        });
      }});
    });
  });
}.call(this), function() {
  $(document).on("ready page:load", function() {
    return $("[data-growl]").on("click", function() {
      var e, t, s, i, r;
      return e = $(this), s = ["title", "message", "namespace", "duration", "close", "location", "style", "size"], r = _.select(s, function(t) {
        return null != e.data("growl-" + t);
      }), t = _.map(r, function(t) {
        return[t, e.data("growl-" + t)];
      }), i = _.object(t), i.title || (i.title = "Notification"), "default" === e.data("growl") ? $.growl(i) : $.growl[e.data("growl")](i);
    });
  });
}.call(this), $(document).on("ready page:load", setupJqUi), function() {
  $(document).on("ready page:load", function() {
    return $("#reset-localstorage").on("click", function() {
      var e;
      return e = "Are you sure you want to reset all widgets state?", BootstrapDialog ? BootstrapDialog.confirm(e, function() {
        return function(e) {
          return e ? (store.clear(), Turbolinks.visit(location.pathname)) : void 0;
        };
      }(this)) : confirm(e) ? (store.clear(), Turbolinks.visit(location.pathname)) : void 0;
    });
  });
}.call(this), $(document).on("ready page:load", function() {
  var e = {theme:"movies", valueField:"title", labelField:"title", searchField:"title", options:[], create:!1, plugins:["nano_scroller"], render:{option:function(e, t) {
    for (var s = [], i = 0, r = e.abridged_cast.length;r > i;i++) {
      s.push("<span>" + t(e.abridged_cast[i].name) + "</span>");
    }
    return'<div class="media"><img class="media-object pull-left" height="80" src="' + t(e.posters.thumbnail) + '" alt=""><div class="media-body"><strong class="media-heading">' + t(e.title) + "</strong><p>" + t((e.synopsis || "No synopsis available at this time.").substring(0, 80)) + "</p><p>" + (s.length ? "Starring " + s.join(", ") : "Actors unavailable") + "</p></div></div>";
  }}, load:function(e, t) {
    return e.length ? void $.ajax({url:"http://api.rottentomatoes.com/api/public/v1.0/movies.json", type:"GET", dataType:"jsonp", data:{q:e, page_limit:10, apikey:"puhkccvuy6p87zp54qr887bn"}, error:function() {
      t();
    }, success:function(e) {
      t(e.movies);
    }}) : t();
  }}, t = $(".selectize-search-movie").selectize(e);
  if (t[0]) {
    var s = t[0].selectize;
    s.on("item_add", function() {
      s.close(), s.clear();
    }), $(".selectize-select-movie").selectize(e), $("#select-car").selectize({options:[{id:"avenger", make:"dodge", model:"Avenger"}, {id:"caliber", make:"dodge", model:"Caliber"}, {id:"caravan-grand-passenger", make:"dodge", model:"Caravan Grand Passenger"}, {id:"challenger", make:"dodge", model:"Challenger"}, {id:"ram-1500", make:"dodge", model:"Ram 1500"}, {id:"viper", make:"dodge", model:"Viper"}, {id:"a3", make:"audi", model:"A3"}, {id:"a6", make:"audi", model:"A6"}, {id:"r8", make:"audi", 
    model:"R8"}, {id:"rs-4", make:"audi", model:"RS 4"}, {id:"s4", make:"audi", model:"S4"}, {id:"s8", make:"audi", model:"S8"}, {id:"tt", make:"audi", model:"TT"}, {id:"avalanche", make:"chevrolet", model:"Avalanche"}, {id:"aveo", make:"chevrolet", model:"Aveo"}, {id:"cobalt", make:"chevrolet", model:"Cobalt"}, {id:"silverado", make:"chevrolet", model:"Silverado"}, {id:"suburban", make:"chevrolet", model:"Suburban"}, {id:"tahoe", make:"chevrolet", model:"Tahoe"}, {id:"trail-blazer", make:"chevrolet", 
    model:"TrailBlazer"}], optgroups:[{id:"dodge", name:"Dodge"}, {id:"audi", name:"Audi"}, {id:"chevrolet", name:"Chevrolet"}], labelField:"model", valueField:"id", optgroupField:"make", optgroupLabelField:"name", optgroupValueField:"id", optgroupOrder:["chevrolet", "dodge", "audi"], searchField:["model"], plugins:["optgroup_columns"]});
  }
}), $(function() {
  function e(e, t) {
    var s, i = [];
    i.push(e.type.toUpperCase() + ' url = "' + e.url + '"');
    for (var r in e.data) {
      if (e.data[r] && "object" == typeof e.data[r]) {
        s = [];
        for (var o in e.data[r]) {
          s.push(o + ': "' + e.data[r][o] + '"');
        }
        s = "{ " + s.join(", ") + " }";
      } else {
        s = '"' + e.data[r] + '"';
      }
      i.push(r + " = " + s);
    }
    i.push("RESPONSE: status = " + t.status), t.responseText && ($.isArray(t.responseText) ? (i.push("["), $.each(t.responseText, function(e, t) {
      i.push("{value: " + t.value + ', text: "' + t.text + '"}');
    }), i.push("]")) : i.push($.trim(t.responseText))), i.push("--------------------------------------\n"), $("#console").val(i.join("\n") + $("#console").val());
  }
  $.mockjaxSettings.responseTime = 500, $.mockjax({url:"/post", response:function(t) {
    e(t, this);
  }}), $.mockjax({url:"/error", status:400, statusText:"Bad Request", response:function(t) {
    this.responseText = "Please input correct value", e(t, this);
  }}), $.mockjax({url:"/status", status:500, response:function(t) {
    this.responseText = "Internal Server Error", e(t, this);
  }}), $.mockjax({url:"/groups", response:function(t) {
    this.responseText = [{value:0, text:"Guest"}, {value:1, text:"Service"}, {value:2, text:"Customer"}, {value:3, text:"Operator"}, {value:4, text:"Support"}, {value:5, text:"Admin"}], e(t, this);
  }});
});
var cb = function() {
  $.fn.editable.defaults.url = "/";
  var e = window.location.href.match(/x-editable-type=inline/i) ? "inline" : "popup", t = "inline" === e ? "inline" : "popup";
  $.fn.editable.defaults.mode = t, $('input[name="x-editable-type"][value="' + t + '"]').prop("checked", !0), $("#enable").click(function() {
    $("#x-editable .editable").editable("toggleDisabled");
  }), $("#x-editablename").editable({url:"/post", type:"text", pk:1, name:"username", title:"Enter username"}), $("#firstname").editable({validate:function(e) {
    return "" == $.trim(e) ? "This field is required" : void 0;
  }}), $("#sex").editable({prepend:"not selected", source:[{value:1, text:"Male"}, {value:2, text:"Female"}], display:function(e, t) {
    var s = {"":"gray", 1:"green", 2:"blue"}, i = $.grep(t, function(t) {
      return t.value == e;
    });
    i.length ? $(this).text(i[0].text).css("color", s[e]) : $(this).empty();
  }}), $("#status").editable(), $("#group").editable({showbuttons:!1}), $("#vacation").editable({datepicker:{todayBtn:"linked"}}), $("#dob").editable(), $("#event").editable({placement:"right", combodate:{firstItem:"name"}}), $("#meeting_start").editable({format:"yyyy-mm-dd hh:ii", viewformat:"dd/mm/yyyy hh:ii", validate:function(e) {
    return e && 10 == e.getDate() ? "Day cant be 10!" : void 0;
  }, datetimepicker:{todayBtn:"linked", weekStart:1}}), $("#comments").editable({showbuttons:"bottom"}), $("#note").editable(), $("#pencil").click(function(e) {
    e.stopPropagation(), e.preventDefault(), $("#note").editable("toggle");
  }), $("#state").editable({source:["Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Dakota", "North Carolina", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island", 
  "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming"]}), $("#state2").editable({value:"California", typeahead:{name:"state", local:["Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", 
  "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Dakota", "North Carolina", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming"]}}), $("#fruits").editable({pk:1, limit:3, source:[{value:1, text:"banana"}, {value:2, text:"peach"}, {value:3, text:"apple"}, {value:4, text:"watermelon"}, {value:5, 
  text:"orange"}]}), $("#tags").editable({inputclass:"input-large", selectize:{valueField:"id", labelField:"title", searchField:"title", options:[{id:1, title:"html"}, {id:2, title:"javascript"}, {id:3, title:"css"}, {id:3, title:"ajax"}]}}), $("#address").editable({url:"/post", value:{city:"Moscow", street:"Lenina", building:"12"}, validate:function(e) {
    return "" == e.city ? "city is required!" : void 0;
  }, display:function(e) {
    if (!e) {
      return void $(this).empty();
    }
    var t = "<b>" + $("<div>").text(e.city).html() + "</b>, " + $("<div>").text(e.street).html() + " st., bld. " + $("<div>").text(e.building).html();
    $(this).html(t);
  }}), $("#x-editable [rel=editable]").on("hidden", function(e, t) {
    if ("save" === t || "nochange" === t) {
      var s = $(this).closest("tr").next().find(".editable");
      $("#autoopen").is(":checked") ? setTimeout(function() {
        s.editable("show");
      }, 300) : s.focus();
    }
  }), $('input[type=radio][name="x-editable-type"]').on("ifChecked", function(e) {
    $el = $(e.currentTarget), $el.closest("form").submit();
  });
};
$(cb), $document = $(document), $document.on("page:load", cb), function() {
  $(document).on("ready page:load", function() {
    var e;
    return e = function(e, t, s) {
      return "false" === s ? !1 : s || t;
    }, $(".easy-pie-chart-normal").each(function() {
      var t, s, i, r, o, n;
      return t = $(this), s = EmVars.colorFromName(t.data("color") || "info"), o = t.data("size") || 150, i = t.data("line-width") || 15, n = e(t, "#f2f2f2", t.data("track-color")), r = e(t, EmVars.colors.textColor, t.data("scale-color")), t.easyPieChart({lineWidth:i, size:o, lineCap:"square", barColor:s, scaleColor:r, animate:1E3, trackColor:n});
    }), $(".easy-pie-chart-percent").easyPieChart({animate:1E3, scaleColor:EmVars.colors.textColor, lineWidth:15, size:150, barColor:function(e) {
      return "rgb(" + Math.round(200 * e / 100) + ", " + Math.round(200 * (1 - e / 100)) + ", 0)";
    }}), setInterval(function() {
      return $(".easy-pie-chart-percent").each(function() {
        var e;
        return e = EmVars.rand(0, 80), $(this).data("easyPieChart").update(e), $(this).find("span").text("" + e + "%");
      });
    }, 2500);
  });
}.call(this), function() {
  $(document).on("ready page:load", function() {
    var e, t, s;
    return s = function(e) {
      var t, s;
      return null == e && (e = !1), t = e ? 2 : 4, _.map(function() {
        s = [];
        for (var e = 1;t >= 1 ? t >= e : e >= t;t >= 1 ? e++ : e--) {
          s.push(e);
        }
        return s;
      }.apply(this), function(t) {
        var s, i;
        return i = 10 * t, s = parseInt(Math.floor(61 * Math.random()) + 10), e ? [s, i] : [i, s];
      });
    }, t = function(e) {
      return null == e && (e = !1), _.map([EmVars.colors.primary.color, EmVars.colors.success.color, EmVars.colors.danger.color], function(t, i) {
        var r;
        return r = EmVars.adjustOpacity(t, .5), {label:"Product " + (i + 1), data:s(e), color:r, bars:{horizontal:e, show:!0, fill:!0, lineWidth:1, order:i + 1, fillColor:r}};
      });
    }, e = {xaxis:{tickLength:0}, yaxis:{}, grid:{hoverable:!0, clickable:!1, borderWidth:0}, legend:{labelBoxBorderColor:"none", position:"left"}, series:{shadowSize:1}, tooltip:!0, tooltipOpts:{defaultTheme:!1}}, $(".flot-bar").each(function() {
      return $.plot($(this), t(), e);
    }), $(".flot-bar-horizontal").each(function() {
      return $.plot($(this), t(!0), e);
    });
  });
}.call(this), function() {
  $(document).on("ready page:load", function() {
    var e;
    return e = function() {
      return _.map([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], function(e) {
        return[e, parseInt(Math.floor(11 * Math.random()) + 10)];
      });
    }, $(".flot-line").each(function() {
      var t, s;
      return t = $(this), s = EmVars.colorFromName(t.data("color") || "info"), $.plot(t, [{data:e()}], {series:{lines:{show:!0, lineWidth:1, fill:!0, fillColor:{colors:[{opacity:.3}, {opacity:.3}]}}, points:{radius:3, show:!0}, grow:{active:!0, steps:50}, shadowSize:2}, grid:{hoverable:!0, clickable:!0, tickColor:EmVars.colors.light.color, borderWidth:1, color:EmVars.colors.light.color}, colors:[s], xaxis:{}, yaxis:{ticks:5}, tooltip:!0, tooltipOpts:{content:"chart: %x.1 is %y.4", defaultTheme:!1, 
      shifts:{x:0, y:20}}});
    });
  });
}.call(this), function() {
  $(document).on("ready page:load", function() {
    var e, t, s, i, r, o;
    return e = [], i = 300, o = 30, t = function() {
      var t, s, r, o;
      for (e.length > 0 && (e = e.slice(1));e.length < i;) {
        s = e.length > 0 ? e[e.length - 1] : 50, o = s + 10 * Math.random() - 5, 0 > o ? o = 0 : o > 100 && (o = 100), e.push(o);
      }
      for (r = [], t = 0;t < e.length;) {
        r.push([t, e[t]]), ++t;
      }
      return r;
    }, r = function() {
      s.setData([t()]), s.draw(), setTimeout(r, o);
    }, $(".flot-live").length && (s = $.plot(".flot-live", [t()], {series:{lines:{show:!0, lineWidth:1, fill:!0, fillColor:{colors:[{opacity:.2}, {opacity:.1}]}}, shadowSize:2}, colors:[EmVars.colorFromName("light-dk")], yaxis:{min:0, max:100}, xaxis:{show:!1}, grid:{tickColor:EmVars.colors.light.color, borderWidth:0}})) && r();
  });
}.call(this), function() {
  $(document).on("ready page:load", function() {
    var e;
    return e = function() {
      return _.map([1, 2, 3, 4, 5, 6], function(e) {
        return[e, parseInt(Math.floor(21 * Math.random()) + 10)];
      });
    }, $(".flot-multi").each(function() {
      return $.plot($(this), [{data:e(), label:"Unique Visits"}, {data:e(), label:"Page Views"}], {series:{lines:{show:!0, lineWidth:1, fill:!0, fillColor:{colors:[{opacity:.3}, {opacity:.3}]}}, points:{show:!0}, shadowSize:2}, grid:{hoverable:!0, clickable:!0, tickColor:EmVars.colors.light.color, borderWidth:0}, colors:[EmVars.colors.success.color, EmVars.colors.primary.color], xaxis:{ticks:15, tickDecimals:0}, yaxis:{ticks:10, tickDecimals:0}, tooltip:!0, tooltipOpts:{content:"'%s' of %x.1 is %y.4", 
      defaultTheme:!1, shifts:{x:0, y:20}}});
    });
  });
}.call(this), function() {
  $(document).on("ready page:load", function() {
    var e, t, s;
    return s = _.map([40, 10, 20, 12, 18], function(e, t) {
      return{label:EmVars.helpers.words[t], data:e};
    }), e = EmVars.colors.info.color, t = _.map([1, 2, 3, 4, 5], function(t) {
      return EmVars.dk(e, 10 * t);
    }), $(".flot-pie-donut").each(function() {
      return $.plot($(this), s, {series:{pie:{innerRadius:.4, show:!0, stroke:{width:0}, label:{show:!0, threshold:.05}}}, colors:t, grid:{hoverable:!0, clickable:!1}, tooltip:!0, tooltipOpts:{content:"%s: %p.0%", defaultTheme:!1, shifts:{x:0, y:20}}});
    }), $(".flot-pie").each(function() {
      return $.plot($(this), s, {series:{pie:{combine:{color:EmVars.colors.textColor, threshold:.05}, show:!0}}, colors:t, legend:{show:!1}, grid:{hoverable:!0, clickable:!1}, tooltip:!0, tooltipOpts:{content:"%s: %p.0%", defaultTheme:!1, shifts:{x:0, y:20}}});
    });
  });
}.call(this), function() {
  $(document).on("ready page:load", function() {
    var e;
    return e = function() {
      return _.map([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15], function(e) {
        return[e, parseInt(Math.floor(21 * Math.random()) + 10)];
      });
    }, $(".flot-spline").each(function() {
      return $.plot($(this), [e(), e()], {series:{lines:{show:!1}, splines:{show:!0, tension:.4, lineWidth:1, fill:.4}, points:{radius:0, show:!0}, shadowSize:2}, grid:{hoverable:!0, clickable:!0, tickColor:EmVars.colors.light.color, borderWidth:1, color:EmVars.colors.light.color}, colors:[EmVars.colors.success.color, EmVars.colors.primary.color], xaxis:{}, yaxis:{ticks:4}, tooltip:!0, tooltipOpts:{content:"%x.1 = %y.4", defaultTheme:!1, shifts:{x:0, y:20}}});
    });
  });
}.call(this), function() {
  $(document).on("ready page:load", function() {
    var e, t, s, i;
    return e = EmVars.helpers.abbrDays, s = function() {
      var t;
      return _.map(function() {
        t = [];
        for (var s = 0, i = e.length;i >= 0 ? i > s : s > i;i >= 0 ? s++ : s--) {
          t.push(s);
        }
        return t;
      }.apply(this), function(e) {
        return[e, parseInt(Math.floor(11 * Math.random()) + 10)];
      });
    }, i = function(e) {
      return Math.round(e);
    }, t = function() {
      return "";
    }, $(".flot-transparent-line").each(function() {
      var r, o, n, a, l, u, c, p;
      return r = $(this), o = {useXAxisLines:!0, useXAxisLabels:!0, useYAxisLines:!0, useYAxisLabels:!0}, (null != r.data("flot-no-x-lines") || null != r.data("flot-no-lines")) && (o.useXAxisLines = !1), (null != r.data("flot-no-x-labels") || null != r.data("flot-no-labels")) && (o.useXAxisLabels = !1), (null != r.data("flot-no-y-lines") || null != r.data("flot-no-lines")) && (o.useYAxisLines = !1), (null != r.data("flot-no-y-labels") || null != r.data("flot-no-labels")) && (o.useYAxisLabels = !1), 
      n = s(), l = _.min(_.map(n, function(e) {
        return e[1];
      })), a = EmVars.colorFromName(r.data("flot-line-color") || "white"), u = EmVars.adjustOpacity(a, .3), c = {}, c.tickColor = o.useXAxisLines ? u : "transparent", o.useXAxisLabels ? (c.ticks = _.map(n, function(t) {
        return[t[0], e[t[0]]];
      }), c.font = {color:a}) : c.tickFormatter = t, p = {min:l - 5, ticks:5}, p.tickColor = o.useYAxisLines ? u : "transparent", o.useYAxisLabels ? (p.font = {color:a}, p.tickFormatter = i) : p.tickFormatter = t, $.plot(r, [{data:n}], {series:{lines:{show:!0, lineWidth:2, fill:!0, fillColor:{colors:[{opacity:0}, {opacity:0}]}}, points:{radius:3, show:!0, fillColor:"white"}, shadowSize:0}, grid:{hoverable:!0, clickable:!0, tickColor:u, borderWidth:0, color:u}, colors:[a], xaxis:c, yaxis:p, tooltip:!0, 
      tooltipOpts:{content:"%y.1MB/s", defaultTheme:!1, shifts:{x:0, y:20}}});
    });
  });
}.call(this), function() {
  $(document).on("ready page:load", function() {
    return $(".em-knob").each(function() {
      var e, t, s, i, r;
      return e = $(this), t = EmVars.colorFromName(e.data("color") || "info"), i = -50, s = 50, r = EmVars.rand(i, s), e.val(r), e.knob({width:100, height:100, thickness:.2, fgColor:t, min:i, max:s});
    });
  });
}.call(this), function() {
  $(document).on("ready page:load", function() {
    return $(".sparkline-bar").each(function() {
      var e, t, s, i, r;
      return e = $(this), i = EmVars.colorFromName(e.data("color") || "danger"), r = "18px", s = "6px", t = "1px", r = "30px", e.sparkline("html", {type:"bar", barColor:i, height:r, barWidth:s, barSpacing:t, zeroAxis:!1});
    });
  });
}.call(this), function() {
  $(document).on("ready page:load", function() {
    var e, t, s;
    return s = $(".sparkline-composite"), s.length ? (e = _.map([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], function() {
      return EmVars.rand(1, 6);
    }), t = _.map([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], function() {
      return EmVars.rand(1, 6);
    }), s.sparklineResponsive(e, {type:"line", width:"100%", height:"200px", fillColor:EmVars.adjustOpacity(EmVars.colorFromName("light-dk"), .5), lineColor:EmVars.colors.white.color, spotRadius:4, valueSpots:e, minSpotColor:EmVars.colors.light.color, maxSpotColor:EmVars.colors.light.color, highlightSpotColor:EmVars.colors.light.color, highlightLineColor:EmVars.colorFromName("light-dk"), normalRangeMin:0, tooltipClassname:"em-sparkline-tooltip"}), s.sparklineResponsive(t, {type:"line", composite:!0, 
    width:"100%", fillColor:EmVars.adjustOpacity(EmVars.colors.primary.color, .1), lineColor:EmVars.colors.white.color, minSpotColor:EmVars.colors.primary.color, maxSpotColor:EmVars.colors.primary.color, highlightSpotColor:EmVars.colors.info.color, highlightLineColor:EmVars.colorFromName("light-dk"), spotRadius:4, valueSpots:t, normalRangeMin:0, tooltipClassname:"em-sparkline-tooltip", responsive:!0})) : void 0;
  });
}.call(this), function() {
  $(document).on("ready page:load", function() {
    var e, t;
    return t = $(".sparkline-line"), e = function() {
      return _.map([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], function() {
        return EmVars.rand(1, 6);
      });
    }, t.each(function() {
      var t, s;
      return t = $(this), s = "100px", t.data("height") && (s = "" + t.data("height") + "px"), t.sparklineResponsive(e(), {type:"line", width:"100%", height:s, lineColor:EmVars.colors.white.color, lineWidth:2, fillColor:"transparent", spotColor:EmVars.colors.white.color, minSpotColor:EmVars.colors.white.color, maxSpotColor:EmVars.colors.white.color, highlightLineColor:EmVars.colors.white.color, highlightSpotColor:EmVars.colors.white.color, spotRadius:4, normalRangeMin:1});
    });
  });
}.call(this), function() {
  $(document).on("ready page:load", function() {
    var e, t;
    return t = $(".sparkline-pie"), t.length ? (e = [EmVars.colors.light.color, EmVars.colorFromName("success-lt"), EmVars.colorFromName("primary-lt")], t.sparklineResponsive([6, 3, 5], {type:"pie", width:"100%", height:"200px", sliceColors:e, offset:10, borderWidth:0, tooltipClassname:"em-sparkline-tooltip"})) : void 0;
  });
}.call(this), function(e) {
  var t = function(e) {
    this.init("address", e, t.defaults);
  };
  e.fn.editableutils.inherit(t, e.fn.editabletypes.abstractinput), e.extend(t.prototype, {render:function() {
    this.$input = this.$tpl.find("input");
  }, value2html:function(t, s) {
    if (!t) {
      return void e(s).empty();
    }
    var i = e("<div>").text(t.city).html() + ", " + e("<div>").text(t.street).html() + " st., bld. " + e("<div>").text(t.building).html();
    e(s).html(i);
  }, html2value:function() {
    return null;
  }, value2str:function(e) {
    var t = "";
    if (e) {
      for (var s in e) {
        t = t + s + ":" + e[s] + ";";
      }
    }
    return t;
  }, str2value:function(e) {
    return e;
  }, value2input:function(e) {
    e && (this.$input.filter('[name="city"]').val(e.city), this.$input.filter('[name="street"]').val(e.street), this.$input.filter('[name="building"]').val(e.building));
  }, input2value:function() {
    return{city:this.$input.filter('[name="city"]').val(), street:this.$input.filter('[name="street"]').val(), building:this.$input.filter('[name="building"]').val()};
  }, activate:function() {
    this.$input.filter('[name="city"]').focus();
  }, autosubmit:function() {
    this.$input.keydown(function(t) {
      13 === t.which && e(this).closest("form").submit();
    });
  }}), t.defaults = e.extend({}, e.fn.editabletypes.abstractinput.defaults, {tpl:'<div class="editable-address"><label><span>City: </span><input type="text" name="city" class="input-small form-control"></label></div><div class="editable-address"><label><span>Street: </span><input type="text" name="street" class="input-small form-control"></label></div><div class="editable-address"><label><span>Building: </span><input type="text" name="building" class="input-mini form-control"></label></div>', inputclass:""}), 
  e.fn.editabletypes.address = t;
}(window.jQuery), function() {
}.call(this);