/**
 * Resolve a consumer component by its exact registered name.
 */
export function resolveCellComponent<Component>(
  components: Readonly<Record<string, Component>>,
  name: string,
): Component {
  if (!Object.hasOwn(components, name) || !components[name]) {
    throw new Error(`DataTable cell component "${name}" is not registered.`)
  }

  return components[name]
}
