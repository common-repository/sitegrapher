(function(w){
    function sitegrapher() {
    	SiteGrapher = [["setOrganizationKey",SiteGrapherParams.organizationKey],["setSiteKey",SiteGrapherParams.siteKey],["trackPageView"]];
        var d=document;
        var j = d.createElement("script"); j.type = "text/javascript"; j.async = true; j.src = "//cdn.sitegrapher.com/sg_v1.js";
        var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(j, s);
    }
    w.addEventListener?w.addEventListener("load",sitegrapher,false):w.attachEvent("onload",sitegrapher);
})(window);
