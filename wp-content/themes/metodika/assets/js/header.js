/**
 * Бургер: открывает нижний ряд шапки, когда пункты не влезают.
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
	}

	function isMobileNav() {
		return window.getComputedStyle(burger).display !== "none";
	}

	burger.addEventListener("click", function () {
		var open = burger.getAttribute("aria-expanded") === "true";
		setOpen(!open);
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
