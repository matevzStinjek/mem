export function transitionWidths (from, to, ratio) {
    return to.map((target, index) => {
        const previous = from[index] || target
        return previous + (target - previous) * ratio
    })
}

export function calculateWidths (count, hoverIndex, totalWidth, maxSize, minSize) {
    if (hoverIndex === null || hoverIndex >= count) {
        const sizes = []
        for (var i = 0; i < count; i++) {
            sizes.push(totalWidth / count)
        }
        return sizes
    } else {
        const numLeft = hoverIndex
        const numRight = count - hoverIndex - 1
        const hoverStartX = Math.max(0, Math.min(totalWidth - maxSize, totalWidth * (hoverIndex + 0.5) / count - maxSize / 2))
        const hoverEndX = totalWidth - hoverStartX - maxSize

        const left = linearValues(hoverStartX, numLeft, maxSize, minSize).reverse()
        const center = [maxSize]
        const right = linearValues(hoverEndX, numRight, maxSize, minSize)

        const visibleWidths = left.concat(center).concat(right)
        const currentWidth = visibleWidths.reduce((s, acc) => s + acc, 0)
        return visibleWidths.map(w => w * totalWidth / currentWidth)
    }
}

export function linearValues (availableWidth, count, max, min) {
    if (count === 1) {
        return [availableWidth]
    }

    const averageWidth = availableWidth / count
    const maxK = 2 * (averageWidth - max) / (count + 1)
    const minK = 2 * (min - averageWidth) / (count - 1)
    const k = Math.max(maxK, minK)
    const offset = averageWidth - k * (count + 1) / 2

    const values = []
    for (var i = 0; i < count; i++) {
        values.push(k * (i + 1) + offset)
    }

    return values
}
