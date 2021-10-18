
  var menues = $(".sidebar-menu").find("a");
  
  for (x in menues)
  {
		if (menues[x].href === window.location.href)
		{
			$(menues[x]).parent().addClass("active");
			
			if ($(menues[x]).parent().parent().hasClass("treeview-menu"))
			{
				$(menues[x]).parent().parent().addClass("menu-open").css("display","block");
			}
		}
  }