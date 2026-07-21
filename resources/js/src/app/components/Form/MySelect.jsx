import React from 'react'

const MySelect = (
    {
        label = "label",
        id="myinput",
        name="myinput",
        placeholder="Select your choice...",
        className="",
        value="",
        onChange=null, 
        onBlur=null,
        options=[],
        error=""
    }
) => {
    const defaultCLass = `bg-gray-50 dark:bg-gray-700 border text-sm rounded-lg dark:placeholder-gray-400 placeholder:text-gray-50 dark:placeholder:text-gray-900
                            focus:ring-blue-500 focus:border-blue-500 block 
                            w-full p-2.5 dark:focus:ring-blue-500 
                            dark:focus:border-blue-500 `
    const normalCase = "border-gray-300 text-gray-900 dark:border-gray-600 dark:text-white "
    const invalidCase = "border-red-500 text-red-500 dark:border-red-600 text-red-500 "
    return (
        <div className="sm:mb-4 mb-2">
            <label htmlFor={id} className={"block mb-2 text-sm font-medium " + (error !== "" ? "text-red-500" : "text-gray-900 dark:text-white")}>
                {label}
            </label>
            <select 
                id={id}
                name={name}
                className={defaultCLass + (error !== "" ? invalidCase : normalCase ) + className}
                defaultValue={value}
                onChange={onChange}
                onBlur={onBlur}
            >
                <option value="">{placeholder}</option>
                {
                    (options && options.length > 0)  ? 
                    options.map((item, key) => (
                        <option key={key} value={item.value ?? "-"}>{item.name ?? "-"}</option>
                    )) : ""
                }
                
            </select>
            <p className="text-xs text-red-500 mt-1 pl-2">{error}</p>

        </div>
    )
}

export default MySelect