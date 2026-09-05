    function handleSubmit(e) {
      e.preventDefault();
      const input = document.getElementById('email-input');
      const wrap = document.getElementById('formWrap');
      const val = input.value.trim();
      const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (!emailRe.test(val)) {
        input.style.outline = '2px solid rgba(255,80,80,0.5)';
        input.focus();
        setTimeout(() => { input.style.outline = ''; }, 1800);
        return;
      }

      // In production, POST to your mailing list endpoint here
      wrap.classList.add('submitted');
    }

    // Allow Enter key submission
    document.getElementById('email-input').addEventListener('keydown', function(e) {
      if (e.key === 'Enter') {
        document.querySelector('.btn-submit').click();
      }
    });