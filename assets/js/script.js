// basic client-side validation
function validateContactForm() {
  const name = document.getElementById('name').value.trim();
  const phone = document.getElementById('phone').value.trim();
  const message = document.getElementById('message').value.trim();
  if (!name || !phone || !message) {
    alert('Please fill name, phone and message.');
    return false;
  }
  return true;
}

