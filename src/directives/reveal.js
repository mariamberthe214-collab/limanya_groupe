// Directive v-reveal : ajoute une animation d'apparition discrète quand
// l'élément entre dans le viewport. Respecte prefers-reduced-motion (géré en CSS).

const observer = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('reveal-visible')
        observer.unobserve(entry.target)
      }
    })
  },
  { threshold: 0.15, rootMargin: '0px 0px -40px 0px' }
)

export const revealDirective = {
  mounted(el, binding) {
    el.classList.add('reveal')
    if (typeof binding.value === 'number') {
      el.style.transitionDelay = `${binding.value}ms`
    }
    observer.observe(el)
  },
  unmounted(el) {
    observer.unobserve(el)
  },
}
