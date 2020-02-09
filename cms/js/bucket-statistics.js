var Dashboard=function() {
    return {
        init:function() {
            var a,
                r;
            !function() {
                if(0!=$("#m_chart_free_vs_used_space").length) {
                    var e=new Chartist.Pie("#m_chart_free_vs_used_space", {
                            series:[
                                {
                                    value:usedSpaceFromTemplate,
                                    className:"custom",
                                    meta: {
                                        color: mApp.getColor("danger")
                                    }
                                },
                                {
                                    value:freeSpaceFromTemplate,
                                    className:"custom", meta: {
                                        color: mApp.getColor("success")
                                    }
                                }
                            ],
                            labels:[1, 2]
                        }
                        , {
                            donut: !0, donutWidth: 17, showLabel: !1
                        }
                    );
                    e.on("draw", function(e) {
                            if("slice"===e.type) {
                                var t=e.element._node.getTotalLength();
                                e.element.attr( {
                                        "stroke-dasharray": t+"px "+t+"px"
                                    }
                                );
                                var a= {
                                        "stroke-dashoffset": {
                                            id: "anim"+e.index, dur: 1e3, from: -t+"px", to: "0px", easing: Chartist.Svg.Easing.easeOutQuint, fill: "freeze", stroke: e.meta.color
                                        }
                                    }
                                ;
                                0!==e.index&&(a["stroke-dashoffset"].begin="anim"+(e.index-1)+".end"), e.element.attr( {
                                        "stroke-dashoffset": -t+"px", stroke: e.meta.color
                                    }
                                ), e.element.animate(a, !1)
                            }
                        }
                    ),
                        e.on("created", function() {
                                window.__anim21278907124&&(clearTimeout(window.__anim21278907124), window.__anim21278907124=null), window.__anim21278907124=setTimeout(e.update.bind(e), 15e3)
                            }
                        )
                }
            }(),
            0!==$("#m_chart_amount_file_types").length && Morris.Donut( {
                    element:"m_chart_amount_file_types",
                    data: infoFromTemplate
                }
            ).on("draw", function(e) {
                    if("slice"===e.type) {
                        var t=e.element._node.getTotalLength();
                        e.element.attr( {
                                "stroke-dasharray": t+"px "+t+"px"
                            }
                        );
                        var a= {
                                "stroke-dashoffset": {
                                    id: "anim"+e.index, dur: 1e3, from: -t+"px", to: "0px", easing: Chartist.Svg.Easing.easeOutQuint, fill: "freeze", stroke: e.meta.color
                                }
                            }
                        ;
                        0!==e.index&&(a["stroke-dashoffset"].begin="anim"+(e.index-1)+".end"), e.element.attr( {
                                "stroke-dashoffset": -t+"px", stroke: e.meta.color
                            }
                        ), e.element.animate(a, !1)
                    }
                }
            )
        }
    }
}

();
jQuery(document).ready(function() {
        Dashboard.init()
    }
);