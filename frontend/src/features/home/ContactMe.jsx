import { FaPaperPlane } from "react-icons/fa6";
import PropTypes from "prop-types";
import { createContact } from "../../services/apiContactMe";
import { useActionData, useFetcher } from "react-router-dom";
import SpinnerMini from "../../ui/SpinnerMini";
import { useEffect, useRef } from "react";
import toast from "react-hot-toast";

ContactMe.propTypes = {
    honeypot: PropTypes.shape({
        token: PropTypes.string.isRequired,
        graceSeconds: PropTypes.number.isRequired,
        honeypotField: PropTypes.string.isRequired,
        tokenField: PropTypes.string.isRequired,
        minDelay: PropTypes.number.isRequired,
    }).isRequired,
};

function ContactMe({ honeypot }) {
    const { honeypotField, token, tokenField } = honeypot;
    const fetcher = useFetcher();
    const formErrors = useActionData();
    const isSubmitting = fetcher.state === "submitting";
    const formRef = useRef();

    useEffect(
        function () {
            if (fetcher.data?.ok) {
                toast.success("Your message sent successfully.");
                formRef.current?.reset();
                formRef.current?.elements["name"]?.focus();
            } else if (fetcher.data && !fetcher.data.ok) {
                Object.values(fetcher.data).forEach((msg) => {
                    toast.error(msg);
                });
            }
        },
        [fetcher.data]
    );

    return (
        <section
            id="contact"
            className="py-20 bg-secondary-dark dark:bg-black text-light"
        >
            <div className="max-w-7xl mx-auto flex items-center justify-center px-6">
                <div className="w-full max-w-lg bg-white dark:bg-dark-blue border-2 border-light rounded-2xl p-8">
                    <h2 className="text-4xl font-bold text-center mb-8">
                        Contact Me
                    </h2>

                    <fetcher.Form
                        method="post"
                        ref={formRef}
                        className="flex flex-col gap-6"
                    >
                        {formErrors && Object.keys(formErrors).length > 0 && (
                            <div className="mb-4 p-3 bg-red-100 text-red-700 rounded">
                                <ul className="list-disc ml-5">
                                    {Object.values(formErrors).map(
                                        (error, idx) => (
                                            <li key={idx}>{error}</li>
                                        )
                                    )}
                                </ul>
                            </div>
                        )}
                        <div className="flex flex-col">
                            <label className="mb-2 text-light text-sm">
                                Name
                            </label>
                            <input
                                type="text"
                                required
                                className="rounded-lg border border-light bg-transparent p-3 text-light placeholder:text-light focus:outline-none focus:ring-2 focus:ring-secondary transition"
                                placeholder="Enter your name"
                                autoComplete="name"
                                name="name"
                                disabled={isSubmitting}
                            />
                        </div>
                        <div className="flex flex-col">
                            <label className="mb-2 text-light text-sm">
                                Email
                            </label>
                            <input
                                type="email"
                                required
                                className="rounded-lg border border-light bg-transparent p-3 text-light placeholder:text-light focus:outline-none focus:ring-2 focus:ring-secondary transition"
                                placeholder="Enter your email"
                                autoComplete="email"
                                name="email"
                                disabled={isSubmitting}
                            />
                        </div>
                        <div className="flex flex-col">
                            <label className="mb-2 text-light text-sm">
                                Message
                            </label>
                            <textarea
                                rows="5"
                                name="messageBody"
                                required
                                className="rounded-lg border border-light bg-transparent p-3 text-light placeholder:text-light focus:outline-none focus:ring-2 focus:ring-secondary transition"
                                placeholder="Your message here..."
                                disabled={isSubmitting}
                            ></textarea>
                        </div>
                        <input type="hidden" name={tokenField} value={token} />
                        <input
                            type="text"
                            name={honeypotField}
                            tabIndex="-1"
                            autoComplete="off"
                            className="absolute left-[-9999px]"
                            aria-hidden="true"
                        />
                        <button
                            type="submit"
                            className="flex justify-center items-center gap-x-2 mt-4 rounded-full bg-primary hover:bg-secondary text-white font-semibold py-3 transition"
                            disabled={isSubmitting}
                        >
                            {isSubmitting ? (
                                <>
                                    <SpinnerMini />
                                    <span>Sending Message</span>
                                </>
                            ) : (
                                <>
                                    <FaPaperPlane className="text-2xl" />
                                    <span>Send Message</span>
                                </>
                            )}
                        </button>
                    </fetcher.Form>
                </div>
            </div>
        </section>
    );
}

export default ContactMe;

// eslint-disable-next-line;
export async function action({ request }) {
    const formData = await request.formData();
    const data = Object.fromEntries(formData);
    const errors = {};
    if (!data.name) errors.name = "Please fill the name field.";
    if (!data.email) errors.email = "Please fill the email field.";

    if (!data.messageBody || data.messageBody.length < 4)
        errors.messageBody =
            "Please leave your message. It must be more than 3 characters.";
    if (Object.keys(errors).length > 0)
        return new Response(JSON.stringify(errors), {
            status: 400,
            headers: { "Content-Type": "application/json" },
        });

    try {
        await createContact(data);
        return new Response(JSON.stringify({ ok: true }), {
            status: 200,
            headers: { "Content-Type": "application/json" },
        });
    } catch (error) {
        return new Response(
            JSON.stringify({ form: error.message || "Server error" }),
            {
                status: error.status || 500,
                headers: { "Content-Type": "application/json" },
            }
        );
    }
}
