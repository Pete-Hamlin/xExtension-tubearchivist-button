if (document.readyState && document.readyState !== "loading") {
  documentReady();
} else {
  document.addEventListener(
    "DOMContentLoaded",
    async () => await documentReady(),
    false,
  );
}

async function documentReady() {
  // __PRINT_LOC_START
  console.log("┆documentReady┆ ┊1┊"); // __PRINT_LOC_END
  var tubearchivistButtons = document.querySelectorAll(
    "#stream .flux a.tubearchivistButton",
  );
  for (var i = 0; i < tubearchivistButtons.length; i++) {
    let tubearchivistButton = tubearchivistButtons[i];
    tubearchivistButton.addEventListener(
      "click",
      async function (e) {
        if (!tubearchivistButton) {
          return;
        }

        var active = tubearchivistButton.closest(".flux");
        if (!active) {
          return;
        }

        e.preventDefault();
        e.stopPropagation();

        await add_to_tubearchivist(tubearchivistButton, active);
      },
      false,
    );
  }

  if (tubearchivist_button_vars.keyboard_shortcut) {
    document.addEventListener("keydown", function (e) {
      if (
        e.ctrlKey ||
        e.metaKey ||
        e.altKey ||
        e.shiftKey ||
        e.target.closest("input, textarea")
      ) {
        return;
      }

      if (e.key === tubearchivist_button_vars.keyboard_shortcut) {
        var active = document.querySelector("#stream .flux.active");
        if (!active) {
          return;
        }

        var tubearchivistButton = active.querySelector("a.tubearchivistButton");
        if (!tubearchivistButton) {
          return;
        }

        add_to_tubearchivist(tubearchivistButton, active);
      }
    });
  }
}

function requestFailed(activeId, tubearchivistButtonImg, loadingAnimation) {
  delete pending_entries[activeId];

  tubearchivistButtonImg.classList.remove("rb_disabled");
  loadingAnimation.classList.add("rb_disabled");

  badAjax(this.status == 403);
}

async function add_to_tubearchivist(tubearchivistButton, active) {
  const url = tubearchivistButton.getAttribute("href");
  // __PRINT_VAR_START
  console.log("┆add_to_tubearchivist┆ ╎url╎ ┊1┊:", url); // __PRINT_VAR_END
  if (!url) {
    return;
  }

  let tubearchivistButtonImg = tubearchivistButton.querySelector("img");
  tubearchivistButtonImg.classList.add("rb_disabled");

  let loadingAnimation = tubearchivistButton.querySelector(".rb_lds-dual-ring");
  loadingAnimation.classList.remove("rb_disabled");

  let activeId = active.getAttribute("id");
  // __PRINT_VAR_START
  console.log("┆add_to_tubearchivist┆ ╎activeId╎ ┊1┊:", activeId); // __PRINT_VAR_END
  if (pending_entries[activeId]) {
    return;
  }

  pending_entries[activeId] = true;
  await fetch(url, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
    },
    body: JSON.stringify({
      _csrf: context.csrf,
    }),
  })
    .then(async (response) => {
      // __PRINT_VAR_START
      console.log("┆add_to_tubearchivist#(anon)┆ ╎response╎ ┊1┊:", response); // __PRINT_VAR_END
      delete pending_entries[activeId];

      tubearchivistButtonImg.classList.remove("rb_disabled");
      loadingAnimation.classList.add("rb_disabled");

      if (!response.ok) {
        // __PRINT_LOC_START
        console.log("┆add_to_tubearchivist#(anon)#if┆ ┊1┊"); // __PRINT_LOC_END
        requestFailed(activeId, tubearchivistButtonImg, loadingAnimation);
        openNotification(
          tubearchivist_button_vars.i18n.failed_to_add_video_to_tubearchivist.replace(
            "%s",
            json.errorCode,
          ),
          "tubearchivist_button_bad",
        );
        return;
      }

      let json = await response.json();
      // __PRINT_VAR_START
      console.log("┆add_to_tubearchivist#(anon)┆ ╎json╎ ┊1┊:", json); // __PRINT_VAR_END
      if (!json) {
        // __PRINT_LOC_START
        console.log("┆add_to_tubearchivist#(anon)#if┆ ┊2┊"); // __PRINT_LOC_END
        requestFailed(activeId, tubearchivistButtonImg, loadingAnimation);
        openNotification(
          tubearchivist_button_vars.i18n.failed_to_add_video_to_tubearchivist.replace(
            "%s",
            json.errorCode,
          ),
          "tubearchivist_button_bad",
        );
        return;
      }

      console.log(tubearchivist_button_vars);
      console.log(json.errorCode);

      switch (json.errorCode) {
        case 200:
        case 201:
        case 202:
        case 301:
          tubearchivistButtonImg.setAttribute(
            "src",
            tubearchivist_button_vars.icons.added_to_tubearchivist,
          );
          const notificationContent =
            tubearchivist_button_vars.i18n.added_video_to_tubearchivist.replace(
              "%s",
              json.response.task_id,
            );
          openNotification(notificationContent, "tubearchivist_button_good");
          break;

        case 401:
          openNotification(
            tubearchivist_button_vars.i18n.relog_required,
            "tubearchivist_button_bad",
          );
          break;

        case 404:
          openNotification(
            tubearchivist_button_vars.i18n.video_not_found,
            "tubearchivist_button_bad",
          );
          break;

        case 500:
          openNotification(
            tubearchivist_button_vars.i18n.failed_to_add_video_to_tubearchivist,
            "tubearchivist_button_bad",
          );
          break;

        default:
          requestFailed(activeId, tubearchivistButtonImg, loadingAnimation);
          break;
      }
    })
    .catch((e) => {
      // __PRINT_VAR_START
      console.log("┆add_to_tubearchivist#(anon)┆ ╎e╎ ┊1┊:", e); // __PRINT_VAR_END
      requestFailed(activeId, tubearchivistButtonImg, loadingAnimation);
    });
}
