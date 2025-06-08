var app = $.spapp({
    defaultView: "#home",
    templateDir: "../frontend/views/"

});


app.run();


$(document).ready(function () {
  if (window.location.hash === "#shop") {
    ShopService.loadCars();
  }

  $(document).on("pageChanged", function (e, pageName) {
    if (pageName === "shop") {
      ShopService.loadCars();
    }
  });
});
