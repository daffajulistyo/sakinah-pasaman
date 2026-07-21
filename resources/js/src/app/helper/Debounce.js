import React from "react";

function useDebounce(cb, delay) {
    const [debounceValue, setDebounceValue] = React.useState(cb);
    React.useEffect(() => {
        const handler = setTimeout(() => {
            setDebounceValue(cb);
        }, delay);
    
        return () => {
            clearTimeout(handler);
        };
    }, [cb, delay]);
    return debounceValue;
}

export {
    useDebounce
}