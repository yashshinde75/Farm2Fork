let deferredPrompt = null;

// ✅ Listen for install prompt availability
window.addEventListener("beforeinstallprompt", (e) => {
  e.preventDefault();
  deferredPrompt = e;
  console.log("PWA install available");
});

// ✅ This function is used by your navbar button
function showA2HS() {
  if (!deferredPrompt) {
    alert("App is already installed or your browser does not support installation.");
    return;
  }

  deferredPrompt.prompt();

  deferredPrompt.userChoice.then((choiceResult) => {
    if (choiceResult.outcome === "accepted") {
      console.log("User accepted installation");
    } else {
      console.log("User dismissed installation");
    }
    deferredPrompt = null;
  });
}
