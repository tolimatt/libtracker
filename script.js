const body = document.querySelector('body'),
      sidebar = document.querySelector('.sidebar'),
      toggle = document.querySelector('.toggle'),
      searchBtn = document.querySelector('.search-box'),
      modeSwitch = document.querySelector('.toggle-switch'),
      modeText = document.querySelector('.mode-text');
      sections = document.querySelectorAll('section');
      navLinks = document.querySelectorAll('.nav-link a');
      
      function activateLink() {
        let index = sections.length;

        while(--index && window.scrollY + 50 < sections[index].offsetTop) {}

        navLinks.forEach((link) => link.classList.remove('active'));
        navLinks[index].classList.add('active');
    }
      
      activateLink();
      window.addEventListener('scroll', activateLink);
      navLinks.forEach(link => {
        link.addEventListener('click', function() {
            navLinks.forEach(link => link.classList.remove('active'));
            this.classList.add('active');
        });
    });


      toggle.addEventListener('click', () => {
        sidebar.classList.toggle('close');
      });
      
      modeSwitch.addEventListener('click', () => {
        body.classList.toggle('dark');

        if (body.classList.contains('dark')) {
          modeText.innerText = 'Light Mode';
        }else {
          modeText.innerText = 'Dark Mode';
        }
      });