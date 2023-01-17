var circliful = function (t) {
    var e = {};

    function i(n) {
        if (e[n]) return e[n].exports;
        var r = e[n] = {
            i: n,
            l: !1,
            exports: {}
        };
        return t[n].call(r.exports, r, r.exports, i), r.l = !0, r.exports
    }
    return i.m = t, i.c = e, i.d = function (t, e, n) {
        i.o(t, e) || Object.defineProperty(t, e, {
            enumerable: !0,
            get: n
        })
    }, i.r = function (t) {
        "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(t, Symbol.toStringTag, {
            value: "Module"
        }), Object.defineProperty(t, "__esModule", {
            value: !0
        })
    }, i.t = function (t, e) {
        if (1 & e && (t = i(t)), 8 & e) return t;
        if (4 & e && "object" == typeof t && t && t.__esModule) return t;
        var n = Object.create(null);
        if (i.r(n), Object.defineProperty(n, "default", {
                enumerable: !0,
                value: t
            }), 2 & e && "string" != typeof t)
            for (var r in t) i.d(n, r, function (e) {
                return t[e]
            }.bind(null, r));
        return n
    }, i.n = function (t) {
        var e = t && t.__esModule ? function () {
            return t.default
        } : function () {
            return t
        };
        return i.d(e, "a", e), e
    }, i.o = function (t, e) {
        return Object.prototype.hasOwnProperty.call(t, e)
    }, i.p = "", i(i.s = 7)
}([function (t, e, i) {
    "use strict";
    Object.defineProperty(e, "__esModule", {
        value: !0
    });
    var n = function () {
        function t() {}
        return t.setAttributes = function (t, e) {
            for (var i = 0, n = Object.entries(e); i < n.length; i++) {
                var r = n[i],
                    o = r[0],
                    s = r[1];
                t.setAttribute(o, s)
            }
        }, t.setAttributeNamespace = function (t, e) {
            for (var i = 0, n = Object.entries(e); i < n.length; i++) {
                var r = n[i],
                    o = r[0],
                    s = r[1];
                t.setAttributeNS(null, o, s)
            }
        }, t.polarToCartesian = function (t, e, i, n) {
            var r = (n - 90) * Math.PI / 180;
            return {
                x: t + i * Math.cos(r),
                y: e + i * Math.sin(r)
            }
        }, t.describeArc = function (e, i, n, r, o, s) {
            void 0 === s && (s = "0");
            var a = t.polarToCartesian(e, i, n, o),
                c = t.polarToCartesian(e, i, n, r),
                d = o - r <= 180 ? "0" : "1",
                u = !1;
            return 360 === o && c.x > a.x && (u = !0, a.x = a.x - .001), ["M", a.x, a.y, "A", n, n, 0, d, s, c.x, c.y, u ? "Z" : ""].join(" ")
        }, t.calculatePathEndCoordinates = function (e, i, n, r) {
            return t.polarToCartesian(e, i, n, r)
        }, t
    }();
    e.default = n
}, function (t, e, i) {
    "use strict";
    Object.defineProperty(e, "__esModule", {
        value: !0
    });
    var n = function () {
        function t() {}
        return t.extractPropertyFromObject = function (t, e) {
            var i;
            return t.hasOwnProperty(e) && t[e] && (i = t[e]), i
        }, t
    }();
    e.default = n
}, function (t, e, i) {
    "use strict";
    Object.defineProperty(e, "__esModule", {
        value: !0
    });
    var n = i(1),
        r = i(0),
        o = function () {
            function t() {}
            return t.addSvg = function (e) {
                var i = document.createElementNS(t.namespaceURI, "svg");
                return e.class = "circle-container " + n.default.extractPropertyFromObject(e, "class"), r.default.setAttributes(i, e), i
            }, t.addCircle = function (e) {
                var i = document.createElementNS(t.namespaceURI, "circle");
                return r.default.setAttributes(i, e), i
            }, t.addArc = function (e) {
                var i = document.createElementNS(t.namespaceURI, "path");
                return r.default.setAttributes(i, e), i
            }, t.addText = function (e) {
                var i = document.createElementNS(t.namespaceURI, "text");
                return i.setAttributeNS(null, "text-anchor", "middle"), r.default.setAttributes(i, e), i
            }, t.addDefs = function (e) {
                var i = document.createElementNS(t.namespaceURI, "defs"),
                    n = document.createElementNS(t.namespaceURI, "linearGradient");
                r.default.setAttributes(n, {
                    id: "linearGradient"
                });
                var o = document.createElementNS(t.namespaceURI, "stop"),
                    s = {
                        offset: "0",
                        "stop-color": e.gradientStart
                    };
                r.default.setAttributes(o, s);
                var a = document.createElementNS(t.namespaceURI, "stop"),
                    c = {
                        offset: "1",
                        "stop-color": e.gradientEnd
                    };
                return r.default.setAttributes(a, c), n.appendChild(o), n.appendChild(a), i.appendChild(n), i
            }, t.namespaceURI = "http://www.w3.org/2000/svg", t
        }();
    e.default = o
}, function (t, e, i) {
    "use strict";
    var n = this && this.__assign || function () {
        return (n = Object.assign || function (t) {
            for (var e, i = 1, n = arguments.length; i < n; i++)
                for (var r in e = arguments[i]) Object.prototype.hasOwnProperty.call(e, r) && (t[r] = e[r]);
            return t
        }).apply(this, arguments)
    };
    Object.defineProperty(e, "__esModule", {
        value: !0
    });
    var r = i(2),
        o = function () {
            function t() {
                this.tags = []
            }
            return t.prototype.animateInView = function () {
                var t = this;
                this.options.animateInView && window.addEventListener("scroll", (function () {
                    t.checkAnimation(t.options.id)
                }))
            }, t.prototype.checkAnimation = function (t) {
                var e = this,
                    i = document.getElementById(t),
                    n = document.getElementById("arc-" + t),
                    r = this.isElementInViewport(i);
                !i.classList.contains("reanimated") && r && (i.classList.add("reanimated"), setTimeout((function () {
                    return e.animate(n)
                }), 250))
            }, t.prototype.isElementInViewport = function (t) {
                var e = t.offsetTop,
                    i = window.scrollY,
                    n = window.innerHeight;
                return i < e && i + n > e
            }, t.prototype.drawContainer = function (t) {
                var e = this.getViewBoxParams(),
                    i = e.minX,
                    o = e.minY,
                    s = e.width,
                    a = e.height,
                    c = r.default.addSvg(n({
                        width: "100%",
                        height: "100%",
                        viewBox: i + " " + o + " " + s + " " + a,
                        id: "svg-" + this.options.id,
                        preserveAspectRatio: "xMinYMin meet"
                    }, t));
                this.tags.push({
                    element: c,
                    parentId: this.options.id
                })
            }, t.prototype.getViewBoxParams = function () {
                var t = this.options,
                    e = t.foregroundCircleWidth,
                    i = t.backgroundCircleWidth,
                    n = i;
                e > i && (n = e);
                var r = this.size.width,
                    o = this.size.height;
                return (e > 5 || i > 5) && (r = this.size.width, o = this.size.height), {
                    minX: 0,
                    minY: 0,
                    width: r,
                    height: o
                }
            }, t.prototype.append = function () {
                this.tags.forEach((function (t) {
                    document.getElementById(t.parentId).appendChild(t.element)
                }))
            }, t.prototype.initialize = function (t, e) {
                this.options = t, this.size = e
            }, t
        }();
    e.BaseCircle = o
}, function (t, e, i) {
    "use strict";
    Object.defineProperty(e, "__esModule", {
        value: !0
    });
    var n = i(0),
        r = function () {
            function t() {}
            return t.animateArc = function (e, i) {
                var r = e.arc,
                    o = e.arcParams,
                    s = e.animationStep,
                    a = e.progressColors,
                    c = o.startAngle ? o.startAngle : 0,
                    d = o.endAngleGrade ? o.endAngleGrade : 360,
                    u = this.getMilliseconds(o.ms, o.endAngleGrade),
                    p = Array.isArray(a) && a.length > 0,
                    l = 1,
                    h = setInterval((function (e, r, a) {
                        var u = d / 100 * l,
                            f = c < 0 && u > 286 ? "1" : "0";
                        n.default.setAttributes(e, {
                            d: n.default.describeArc(o.x, o.y, o.radius, c, u, f)
                        }), p && t.updateCircleColor(l, e, a), ((l += s) > r || l > 100) && (clearInterval(h), "function" == typeof i && i())
                    }), u, r, o.percent, a)
            }, t.updateCircleColor = function (t, e, i) {
                var r = i.find((function (e) {
                    return e.percent === t
                }));
                r && n.default.setAttributes(e, {
                    style: "stroke: " + r.color
                })
            }, t.getMilliseconds = function (t, e) {
                var i = t || 50;
                return e <= 180 && (i /= 3), i
            }, t
        }();
    e.StyleHelper = r
}, function (t, e, i) {
    "use strict";
    Object.defineProperty(e, "__esModule", {
        value: !0
    });
    var n = i(9),
        r = i(10),
        o = i(14),
        s = function () {
            function t() {}
            return t.getParentSize = function (t) {
                return {
                    maxSize: 100,
                    height: 100,
                    width: 100
                }
            }, t.initializeCircleType = function (e, i) {
                void 0 === i && (i = !1);
                var n = t.getParentSize(e.id),
                    s = r.CircleFactory.create(e.type),
                    a = (new o.default).mergeOptions(e, i);
                return s.initialize(a, n), s.drawCircle(), s
            }, t.prototype.newCircle = function (e) {
                return t.initializeCircleType(e), new n.Api(e)
            }, t.prototype.newCircleWithDataSet = function (e, i) {
                var r = {
                    id: e,
                    type: i,
                    percent: 1
                };
                return t.initializeCircleType(r, !0), new n.Api(r)
            }, t
        }();
    e.default = s
}, function (t, e, i) {
    "use strict";
    var n, r = this && this.__extends || (n = function (t, e) {
        return (n = Object.setPrototypeOf || {
                __proto__: []
            }
            instanceof Array && function (t, e) {
                t.__proto__ = e
            } || function (t, e) {
                for (var i in e) e.hasOwnProperty(i) && (t[i] = e[i])
            })(t, e)
    }, function (t, e) {
        function i() {
            this.constructor = t
        }
        n(t, e), t.prototype = null === e ? Object.create(e) : (i.prototype = e.prototype, new i)
    });
    Object.defineProperty(e, "__esModule", {
        value: !0
    });
    var o = i(3),
        s = i(2),
        a = i(1),
        c = i(4),
        d = i(0),
        u = function (t) {
            function e() {
                var e = null !== t && t.apply(this, arguments) || this;
                return e.coordinates = {
                    x: 0,
                    y: 0
                }, e.additionalCssClasses = {}, e
            }
            return r(e, t), e.prototype.initialize = function (e, i) {
                t.prototype.initialize.call(this, e, i);
                var n = this.size.maxSize;
                this.coordinates = {
                    x: n / 2,
                    y: n / 2
                }, this.radius = n / 2.2, this.options.additionalCssClasses && (this.additionalCssClasses = this.options.additionalCssClasses), this.animateInView()
            }, e.prototype.drawCircle = function () {
                var t = {
                    class: a.default.extractPropertyFromObject(this.additionalCssClasses, "svgContainer")
                };
                this.drawContainer(t), this.options.strokeGradient && this.drawLinearGradient(), this.drawBackgroundCircle(), this.drawForegroundCircle(), this.options.point && this.drawPoint(), this.options.icon && this.drawIcon(), this.drawText(), !this.options.textReplacesPercentage && this.options.text && this.drawInfoText(), this.append()
            }, e.prototype.drawBackgroundCircle = function () {
                var t = a.default.extractPropertyFromObject(this.additionalCssClasses, "backgroundCircle"),
                    e = s.default.addCircle({
                        id: "circle-" + this.options.id,
                        class: "background-circle " + t,
                        cx: String(this.coordinates.x),
                        cy: String(this.coordinates.y),
                        r: String(this.radius),
                        "stroke-width": this.options.backgroundCircleWidth
                    });
                this.tags.push({
                    element: e,
                    parentId: "svg-" + this.options.id
                })
            }, e.prototype.drawPoint = function () {
                var t = this.radius / 100 * this.options.pointSize,
                    e = a.default.extractPropertyFromObject(this.additionalCssClasses, "point"),
                    i = s.default.addCircle({
                        id: "point-" + this.options.id,
                        class: "point-circle " + e,
                        cx: String(this.coordinates.x),
                        cy: String(this.coordinates.y),
                        r: String(t)
                    });
                this.tags.push({
                    element: i,
                    parentId: "svg-" + this.options.id
                })
            }, e.prototype.drawForegroundCircle = function () {
                var t = 3.6 * this.options.percent + Number(this.options.startAngle),
                    e = this.options.startAngle ? this.options.startAngle : 0,
                    i = a.default.extractPropertyFromObject(this.additionalCssClasses, "foregroundCircle"),
                    n = {
                        id: "arc-" + this.options.id,
                        class: "foreground-circle " + i,
                        d: d.default.describeArc(this.coordinates.x, this.coordinates.y, this.radius, e, t),
                        "stroke-width": this.options.foregroundCircleWidth,
                        "stroke-linecap": this.options.strokeLinecap
                    };
                this.options.strokeGradient && (n.stroke = "url(#linearGradient)", n.class = "foreground-circle-without-stroke-color " + i);
                var r = s.default.addArc(n);
                this.options.animation && !this.options.startAngle ? this.animate(r) : this.drawArc(r), this.tags.push({
                    element: r,
                    parentId: "svg-" + this.options.id
                })
            }, e.prototype.drawArc = function (t) {
                var e = {
                        percent: this.options.percent,
                        x: this.coordinates.x,
                        y: this.coordinates.y,
                        radius: this.radius
                    },
                    i = 3.6 * this.options.percent;
                d.default.setAttributes(t, {
                    d: d.default.describeArc(e.x, e.y, e.radius, 0, i, "0")
                })
            }, e.prototype.animate = function (t, e) {
                c.StyleHelper.animateArc({
                    arc: t,
                    arcParams: {
                        percent: e || this.options.percent,
                        x: this.coordinates.x,
                        y: this.coordinates.y,
                        radius: this.radius
                    },
                    animationStep: this.options.animationStep,
                    progressColors: this.options.progressColors
                }, this.options.onAnimationEnd)
            }, e.prototype.drawIcon = function () {
                var t = this.options.icon,
                    e = a.default.extractPropertyFromObject(this.additionalCssClasses, "icon"),
                    i = s.default.addText({
                        id: "text-" + this.options.id,
                        x: String(this.coordinates.x),
                        y: String(this.coordinates.y - 25),
                        class: "circle-icon fa " + e
                    });
                i.innerHTML = "&#x" + t + ";", this.tags.push({
                    element: i,
                    parentId: "svg-" + this.options.id
                })
            }, e.prototype.drawText = function () {
                var t = a.default.extractPropertyFromObject(this.additionalCssClasses, "text"),
                    e = s.default.addText({
                        id: "text-" + this.options.id,
                        x: String(this.coordinates.x),
                        y: String(this.coordinates.y),
                        class: "circle-text " + t
                    }),
                    i = this.options.noPercentageSign ? "" : "%",
                    n = "" + this.options.percent + i;
                this.options.textReplacesPercentage && this.options.text && (n = this.options.text), e.textContent = n, this.tags.push({
                    element: e,
                    parentId: "svg-" + this.options.id
                })
            }, e.prototype.drawInfoText = function () {
                var t = a.default.extractPropertyFromObject(this.additionalCssClasses, "infoText"),
                    e = s.default.addText({
                        id: "text-" + this.options.id,
                        x: String(this.coordinates.x),
                        y: String(this.coordinates.y + 20),
                        class: "circle-info-text " + t
                    });
                e.textContent = this.options.text, this.tags.push({
                    element: e,
                    parentId: "svg-" + this.options.id
                })
            }, e.prototype.drawLinearGradient = function () {
                var t = {};
                t.gradientStart = this.options.strokeGradient[0], t.gradientEnd = this.options.strokeGradient[1];
                var e = s.default.addDefs(t);
                this.tags.push({
                    element: e,
                    parentId: "svg-" + this.options.id
                })
            }, e
        }(o.BaseCircle);
    e.default = u
}, function (t, e, i) {
    "use strict";
    Object.defineProperty(e, "__esModule", {
        value: !0
    }), i(8);
    var n = i(5);
    e.newCircle = function (t) {
        return (new n.default).newCircle(t)
    }, e.newCircleWithDataSet = function (t, e) {
        return (new n.default).newCircleWithDataSet(t, e)
    }
}, function (t, e, i) {}, function (t, e, i) {
    "use strict";
    Object.defineProperty(e, "__esModule", {
        value: !0
    });
    var n = i(5),
        r = i(1),
        o = function () {
            function t(t) {
                this.options = t
            }
            return t.prototype.update = function (t) {
                var e = this;
                document.getElementById("svg-" + this.options.id).remove(), Array.isArray(t) ? t.forEach((function (t) {
                    return e.updateType(t.type, t.value)
                })) : this.updateType(t.type, t.value), n.default.initializeCircleType(this.options)
            }, t.prototype.updateType = function (t, e) {
                switch (t) {
                    case "percent":
                        this.options.percent = Number(e);
                        break;
                    case "point":
                        this.options.point = Boolean(e);
                        break;
                    case "animation":
                        this.options.animation = Boolean(e);
                        break;
                    case "pointSize":
                        this.options.pointSize = Number(e);
                        break;
                    case "animationStep":
                        this.options.animationStep = Number(e);
                        break;
                    case "strokeGradient":
                        this.options.strokeGradient = e;
                        break;
                    case "icon":
                        this.options.icon = String(e);
                        break;
                    case "text":
                        this.options.text = String(e);
                        break;
                    case "textReplacesPercentage":
                        this.options.textReplacesPercentage = Boolean(e);
                        break;
                    case "foregroundCircleWidth":
                        this.options.foregroundCircleWidth = Number(e);
                        break;
                    case "backgroundCircleWidth":
                        this.options.backgroundCircleWidth = Number(e);
                        break;
                    case "additionalCssClasses":
                        this.options.additionalCssClasses = e;
                        break;
                    case "progressColors":
                        this.options.progressColors = e
                }
            }, t.prototype.get = function (t) {
                return r.default.extractPropertyFromObject(this.options, t)
            }, t
        }();
    e.Api = o
}, function (t, e, i) {
    "use strict";
    Object.defineProperty(e, "__esModule", {
        value: !0
    });
    var n = i(11),
        r = i(12),
        o = i(13),
        s = i(6),
        a = function () {
            function t() {}
            return t.create = function (t) {
                var e;
                switch (t.toLowerCase()) {
                    case "half":
                        e = new r.default;
                        break;
                    case "plain":
                        e = new o.default;
                        break;
                    case "simple":
                        e = new s.default;
                        break;
                    case "fraction":
                        e = new n.default;
                        break;
                    default:
                        e = new s.default
                }
                return e
            }, t
        }();
    e.CircleFactory = a
}, function (t, e, i) {
    "use strict";
    var n, r = this && this.__extends || (n = function (t, e) {
        return (n = Object.setPrototypeOf || {
                __proto__: []
            }
            instanceof Array && function (t, e) {
                t.__proto__ = e
            } || function (t, e) {
                for (var i in e) e.hasOwnProperty(i) && (t[i] = e[i])
            })(t, e)
    }, function (t, e) {
        function i() {
            this.constructor = t
        }
        n(t, e), t.prototype = null === e ? Object.create(e) : (i.prototype = e.prototype, new i)
    });
    Object.defineProperty(e, "__esModule", {
        value: !0
    });
    var o = i(3),
        s = i(2),
        a = i(0),
        c = function (t) {
            function e() {
                var e = null !== t && t.apply(this, arguments) || this;
                return e.coordinates = {
                    x: 0,
                    y: 0
                }, e.additionalCssClasses = {}, e
            }
            return r(e, t), e.isOdd = function (t) {
                return t % 2
            }, e.prototype.initialize = function (e, i) {
                t.prototype.initialize.call(this, e, i);
                var n = this.size.maxSize;
                this.coordinates = {
                    x: n / 2,
                    y: n / 2
                }, this.radius = n / 2.2, this.options.additionalCssClasses && (this.additionalCssClasses = this.options.additionalCssClasses), this.animateInView()
            }, e.prototype.drawCircle = function () {
                this.drawContainer(), this.drawFraction(), this.append()
            }, e.prototype.drawFraction = function () {
                this.fractionAngle = 360 / this.options.fractionCount;
                for (var t = 0; t < this.options.fractionCount; t++) {
                    this.rotateDegree = this.fractionAngle * t;
                    var i = this.options.fillColor;
                    if (this.options.fractionColors && this.options.fractionColors.length >= 2) {
                        var n = this.options.fractionColors;
                        i = e.isOdd(t) ? n[0] : n[1]
                    }
                    t >= this.options.fractionFilledCount && (i = "none"), this.drawArc(i)
                }
            }, e.prototype.drawArc = function (t) {
                var e = s.default.addArc({
                    id: "arc-" + this.options.id,
                    class: "fraction",
                    d: a.default.describeArc(this.coordinates.x, this.coordinates.y, this.radius, 0, this.fractionAngle) + this.getLineToCenter(),
                    "stroke-width": this.options.foregroundCircleWidth,
                    fill: t,
                    stroke: this.options.strokeColor,
                    transform: "rotate(" + this.rotateDegree + ", " + this.coordinates.x + ", " + this.coordinates.y + ")"
                });
                this.tags.push({
                    element: e,
                    parentId: "svg-" + this.options.id
                })
            }, e.prototype.getLineToCenter = function () {
                var t = a.default.calculatePathEndCoordinates(this.coordinates.x, this.coordinates.y, this.radius, this.fractionAngle);
                return " L " + this.coordinates.y + " " + this.coordinates.x + " M " + t.x + " " + t.y + " L " + this.coordinates.y + " " + this.coordinates.x
            }, e.prototype.animate = function (t) {}, e
        }(o.BaseCircle);
    e.default = c
}, function (t, e, i) {
    "use strict";
    var n, r = this && this.__extends || (n = function (t, e) {
        return (n = Object.setPrototypeOf || {
                __proto__: []
            }
            instanceof Array && function (t, e) {
                t.__proto__ = e
            } || function (t, e) {
                for (var i in e) e.hasOwnProperty(i) && (t[i] = e[i])
            })(t, e)
    }, function (t, e) {
        function i() {
            this.constructor = t
        }
        n(t, e), t.prototype = null === e ? Object.create(e) : (i.prototype = e.prototype, new i)
    });
    Object.defineProperty(e, "__esModule", {
        value: !0
    });
    var o = i(2),
        s = i(1),
        a = i(4),
        c = i(0),
        d = function (t) {
            function e() {
                return null !== t && t.apply(this, arguments) || this
            }
            return r(e, t), e.prototype.drawCircle = function () {
                var t = {
                    class: s.default.extractPropertyFromObject(this.additionalCssClasses, "svgContainer")
                };
                this.drawContainer(t), this.drawBackgroundCircle(), this.drawForegroundCircle(), this.drawText(), this.append()
            }, e.prototype.drawBackgroundCircle = function () {
                var t = s.default.extractPropertyFromObject(this.additionalCssClasses, "backgroundCircle"),
                    e = o.default.addArc({
                        id: "bg-arc-" + this.options.id,
                        d: c.default.describeArc(this.coordinates.x, this.coordinates.y, this.radius, 270, 90),
                        class: "background-circle " + t,
                        "stroke-width": this.options.backgroundCircleWidth
                    });
                this.tags.push({
                    element: e,
                    parentId: "svg-" + this.options.id
                })
            }, e.prototype.drawForegroundCircle = function () {
                var t = 1.8 * this.options.percent,
                    e = s.default.extractPropertyFromObject(this.additionalCssClasses, "foregroundCircle"),
                    i = o.default.addArc({
                        id: "arc-" + this.options.id,
                        class: "foreground-circle " + e,
                        d: c.default.describeArc(this.coordinates.x, this.coordinates.y, this.radius, 0, t),
                        transform: "rotate(-90, " + this.coordinates.x + ", " + this.coordinates.y + ")",
                        "stroke-width": this.options.foregroundCircleWidth,
                        "stroke-linecap": this.options.strokeLinecap
                    });
                this.options.animation && this.animate(i), this.tags.push({
                    element: i,
                    parentId: "svg-" + this.options.id
                })
            }, e.prototype.animate = function (t) {
                a.StyleHelper.animateArc({
                    arc: t,
                    arcParams: {
                        percent: this.options.percent,
                        x: this.coordinates.x,
                        y: this.coordinates.y,
                        radius: this.radius,
                        endAngleGrade: 180
                    },
                    animationStep: this.options.animationStep,
                    progressColors: this.options.progressColors
                }, this.options.onAnimationEnd)
            }, e
        }(i(6).default);
    e.default = d
}, function (t, e, i) {
    "use strict";
    var n, r = this && this.__extends || (n = function (t, e) {
        return (n = Object.setPrototypeOf || {
                __proto__: []
            }
            instanceof Array && function (t, e) {
                t.__proto__ = e
            } || function (t, e) {
                for (var i in e) e.hasOwnProperty(i) && (t[i] = e[i])
            })(t, e)
    }, function (t, e) {
        function i() {
            this.constructor = t
        }
        n(t, e), t.prototype = null === e ? Object.create(e) : (i.prototype = e.prototype, new i)
    });
    Object.defineProperty(e, "__esModule", {
        value: !0
    });
    var o = i(3),
        s = i(2),
        a = i(1),
        c = i(4),
        d = i(0),
        u = function (t) {
            function e() {
                var e = null !== t && t.apply(this, arguments) || this;
                return e.coordinates = {
                    x: 0,
                    y: 0
                }, e.additionalCssClasses = {}, e
            }
            return r(e, t), e.prototype.initialize = function (e, i) {
                t.prototype.initialize.call(this, e, i);
                var n = this.size.maxSize;
                this.coordinates = {
                    x: n / 2,
                    y: n / 2
                }, this.radius = n / 2.2, this.options.additionalCssClasses && (this.additionalCssClasses = this.options.additionalCssClasses), this.animateInView()
            }, e.prototype.drawCircle = function () {
                this.drawContainer(), this.drawPlainCircle(), this.append()
            }, e.prototype.drawPlainCircle = function () {
                var t = this.options.startAngle ? this.options.startAngle : 0,
                    e = 3.6 * this.options.percent + Number(t),
                    i = a.default.extractPropertyFromObject(this.additionalCssClasses, "foregroundCircle"),
                    n = s.default.addArc({
                        id: "arc-" + this.options.id,
                        class: "foreground-circle " + i,
                        d: d.default.describeArc(this.coordinates.x, this.coordinates.y, this.radius, t, e),
                        "stroke-width": this.options.foregroundCircleWidth,
                        "stroke-linecap": this.options.strokeLinecap
                    });
                this.options.animation && !this.options.startAngle && this.animate(n), this.tags.push({
                    element: n,
                    parentId: "svg-" + this.options.id
                })
            }, e.prototype.animate = function (t) {
                c.StyleHelper.animateArc({
                    arc: t,
                    arcParams: {
                        percent: this.options.percent,
                        x: this.coordinates.x,
                        y: this.coordinates.y,
                        radius: this.radius
                    },
                    animationStep: this.options.animationStep,
                    progressColors: this.options.progressColors
                }, this.options.onAnimationEnd)
            }, e
        }(o.BaseCircle);
    e.default = u
}, function (t, e, i) {
    "use strict";
    var n = this && this.__assign || function () {
        return (n = Object.assign || function (t) {
            for (var e, i = 1, n = arguments.length; i < n; i++)
                for (var r in e = arguments[i]) Object.prototype.hasOwnProperty.call(e, r) && (t[r] = e[r]);
            return t
        }).apply(this, arguments)
    };
    Object.defineProperty(e, "__esModule", {
        value: !0
    });
    var r = function () {
        function t() {
            this.defaultOptions = {
                point: !1,
                pointSize: 60,
                percent: 75,
                foregroundCircleWidth: 5,
                backgroundCircleWidth: 15,
                animation: !0,
                animationStep: 1,
                noPercentageSign: !1,
                animateInView: !1,
                strokeLinecap: "butt",
                type: "SimpleCircle",
                textReplacesPercentage: !1
            }
        }
        return t.getDataAttributes = function (t) {
            var e = document.getElementById(t.id),
                i = {
                    percent: t.percent
                };
            for (var n in e.dataset)
                if (e.dataset.hasOwnProperty(n)) {
                    var r = e.dataset[n];
                    "false" === r || "true" === r ? i[n] = "true" === r : Number(r) ? i[n] = Number(r) : i[n] = r
                } return i
        }, t.prototype.mergeOptions = function (e, i) {
            void 0 === i && (i = !1);
            var r = n(n({}, this.defaultOptions), e);
            if (i) {
                var o = t.getDataAttributes(e);
                r = n(n({}, r), o)
            }
            return r
        }, t
    }();
    e.default = r
}]);

