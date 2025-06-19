  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-bs-toggle="modal"]').forEach(btn => {
      btn.addEventListener('click', () => {
        const target = document.querySelector(btn.getAttribute('data-bs-target'));
        target.classList.add('show');
        target.style.display = 'block';

        // backdrop
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        document.body.appendChild(backdrop);
      });
    });

    document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(btn => {
      btn.addEventListener('click', () => {
        const modal = btn.closest('.modal');
        modal.classList.remove('show');
        modal.style.display = 'none';

        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) backdrop.remove();
      });
    });
  });
