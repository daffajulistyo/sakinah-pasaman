import React from 'react'
import { Editor } from '@tinymce/tinymce-react';

const TinyMceEditor = ({
    id="id",
    label="Label",
    initialValue="<p>This is the initial content of the editor.</p>",
    error="",
    onChange
}) => {
    const editorRef = React.useRef(null);
    const isFirstRender = React.useRef(true);
    const apiKey = import.meta.env.VITE_TINYMCE_APIKEY
    const internalOnchange = (e) => {
        onChange(e.target.getContent())
    }

    React.useEffect(() => {
        if (editorRef.current && !isFirstRender.current) {
            const currentContent = editorRef.current.getContent()
            if (currentContent !== initialValue) {
                editorRef.current.setContent(initialValue)
            }
        }
    }, [initialValue])

    return (
        <div className="sm:mb-4 mb-2">
            <label htmlFor={id} 
                className={"block text-sm font-medium leading-6 " + (error !== "" ? "text-red-500" : "text-gray-900 dark:text-white")}>
                {label}
            </label>
            <div className="mt-2">
                <Editor
                    apiKey={apiKey}
                    onInit={(evt, editor) => {
                        editorRef.current = editor
                        isFirstRender.current = false
                    }}
                    initialValue={initialValue}
                    init={{
                    height: 300,
                    menubar: false,
                    plugins: [
                        'advlist autolink lists link image charmap print preview anchor',
                        'searchreplace visualblocks code fullscreen',
                        'insertdatetime media table paste code help wordcount'
                    ],
                    toolbar: 'undo redo | formatselect | ' +
                    'bold italic backcolor | alignleft aligncenter ' +
                    'alignright alignjustify | bullist numlist outdent indent | ' +
                    'removeformat | help',
                    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
                    }}
                    onChange={internalOnchange}
                />
            </div>
        </div>
    )
}

export default TinyMceEditor