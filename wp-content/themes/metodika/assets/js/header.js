/**
 * Бургер и выпадающие пункты меню на узком экране.
 */
(function () {
	var burger = document.querySelector(".header-burger");
	var panel = document.getElementById("header-panel");

	if (!burger || !panel) {
		return;
	}

	function setOpen(open) {
		burger.setAttribute("aria-expanded", open ? "true" : "false");
		panel.classList.toggle("is-open", open);
		document.body.classList.toggle("is-menu-open", open);

		if (!open) {
			panel.querySelectorAll(".header-menu__item--parent.is-open").forEach(function (item) {
				item.classList.remove("is-open");
				var link = item.querySelector(":scope > .header-menu__link");
				if (link) {
					link.setAttribute("aria-expanded", "false");
				}
			});
		}
	}

	function isMobileNav() {
		return window.getComputedStyle(burger).display !== "none";
	}

	burger.addEventListener("click", function () {
		var open = burger.getAttribute("aria-expanded") === "true";
		setOpen(!open);
	});

	panel.querySelectorAll(".header-menu__item--parent > .header-menu__link").forEach(function (link) {
		link.addEventListener("click", function (event) {
			if (!isMobileNav()) {
				return;
			}

			var item = link.parentElement;
			var sub = item.querySelector(".header-menu__sub");
			if (!sub) {
				return;
			}

			event.preventDefault();
			var open = !item.classList.contains("is-open");
			item.classList.toggle("is-open", open);
			link.setAttribute("aria-expanded", open ? "true" : "false");
		});
	});

	document.addEventListener("keydown", function (event) {
		if (event.key === "Escape") {
			setOpen(false);
		}
	});

	window.addEventListener("resize", function () {
		if (!isMobileNav()) {
			setOpen(false);
		}
	});
})();
