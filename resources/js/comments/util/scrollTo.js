export default function scrollTo (target) {
  if (target instanceof Element) {
    target.scrollIntoView({ behavior: 'smooth' })
  } else {
    window.scrollTo({ top: target, behavior: 'smooth' })
  }
}
