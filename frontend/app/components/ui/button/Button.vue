<script setup lang="ts">
withDefaults(defineProps<{
  type?: 'button' | 'submit' | 'reset'
  asChild?: boolean
  variant?: 'default' | 'ghost' | 'outline' | 'secondary' | 'destructive'
  size?: 'default' | 'sm' | 'icon'
  disabled?: boolean
}>(), {
  type: 'button',
  asChild: false,
  variant: 'default',
  size: 'default',
})
</script>

<template>
  <component
    :is="asChild ? 'span' : 'button'"
    :type="asChild ? undefined : type"
    :disabled="asChild ? undefined : disabled"
    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
    :class="[
      {
        default: 'bg-primary text-primary-foreground hover:bg-primary/90',
        ghost: 'text-muted-foreground hover:bg-accent hover:text-accent-foreground',
        outline: 'border bg-card hover:bg-accent hover:text-accent-foreground',
        secondary: 'bg-secondary text-secondary-foreground hover:bg-secondary/80',
        destructive: 'bg-danger text-danger-foreground hover:bg-danger/90',
      }[variant],
      {
        default: 'h-10 px-4 py-2 text-sm',
        sm: 'h-9 px-3 text-sm',
        icon: 'size-10',
      }[size],
    ]"
  >
    <slot />
  </component>
</template>
