import React from 'react'

const MyTextarea = (
    { 
        label = "label",
        id="myinput",
        name="myinput",
        placeholder="input your text...",
        className="",
        value="",
        onChange,
        onBlur = null,
        rows="4",
        error="",
        disabled=false
    }
) => {
    const defaultClass = `block p-2.5 w-full text-sm bg-gray-50 rounded-lg border  
                    focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 
                    dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500 `
    
    const normalCase = "border-gray-300 text-gray-900 dark:border-gray-600 dark:text-white "
    const invalidCase = "border-red-500 text-red-500 dark:border-red-600 text-red-500 "

    return (
        <div className="sm:mb-4 mb-2">
            <label htmlFor={id} className={"block mb-2 text-sm font-medium " +  (error !== "" ? "text-red-500" : "text-gray-900 dark:text-white")}>
                {label}
            </label>
            <textarea 
                id={id} 
                rows={rows}
                name={name}
                className={defaultClass + (error !== "" ? invalidCase : normalCase ) + className} 
                placeholder={placeholder}
                onChange={onChange}
                defaultValue={value}
                value={value}
                onBlur={onBlur}
                readOnly={disabled}
            >
                
            </textarea>
            <p className="text-xs text-red-500 mt-1 pl-2">{error}</p>
        </div>
    )
}

export default MyTextarea