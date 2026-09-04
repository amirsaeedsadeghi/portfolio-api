import PropTypes from "prop-types";
import { useEffect, useRef } from "react";

import TerminalPrompt from "./TerminalPrompt";
import TerminalWindow from "./TerminalWindow";
import useSocialShell, { SHELL_STAGE } from "../../hooks/useSocialShell";
import { formatTerminalDate } from "../../utils/terminalDate";

SocialTerminal.propTypes = {
    aboutMe: PropTypes.object.isRequired,
};

function SocialTerminal({ aboutMe }) {
    const terminalContentRef = useRef(null);

    const {stage,isProcessing,isCurrentOutputVisible,executeCurrentCommand,exitShell} = useSocialShell();

    const { linkedinUrl, githubUrl } = aboutMe;

    const sessionTime = formatTerminalDate();

    useEffect(() => {
        const terminal = terminalContentRef.current;

        if (!terminal) {
            return;
        }

        terminal.scrollTo({
            top: terminal.scrollHeight,
            behavior: "smooth",
        });
    }, [stage, isCurrentOutputVisible]);

    const isWhoisOutputVisible =
        stage > SHELL_STAGE.WHOIS ||
        (stage === SHELL_STAGE.WHOIS && isCurrentOutputVisible);

    const isSocialCommandVisible = stage >= SHELL_STAGE.SOCIAL;

    const isSocialOutputVisible =
        stage > SHELL_STAGE.SOCIAL ||
        (stage === SHELL_STAGE.SOCIAL && isCurrentOutputVisible);

    const isLogoutCommandVisible = stage >= SHELL_STAGE.LOGOUT;

    const isLogoutOutputVisible =
        stage === SHELL_STAGE.COMPLETE ||
        (stage === SHELL_STAGE.LOGOUT && isCurrentOutputVisible);

    return (
        <TerminalWindow contentRef={terminalContentRef}>
            <div className="terminal-font text-[13px] leading-6 text-[#d6deeb] sm:text-sm md:text-[15px] md:leading-7">
                <div className="mb-6">
                    <p>Last login: {sessionTime} on ttys001</p>

                    <p className="mt-4 text-[#addb67]">
                        Welcome to Amirsaeed Sadeghi Komjani Social Shell v2.1
                    </p>
                </div>

                {/* WHOIS */}
                <TerminalPrompt
                    command="whois amirsaeedkomjani.ir"
                    active={stage === SHELL_STAGE.WHOIS && !isProcessing}
                />

                {isWhoisOutputVisible && (
                    <div className="my-5">
                        <p>
                            <span className="text-[#637777]">
                                Domain Owner:
                            </span>{" "}
                            Amirsaeed Sadeghi Komjani
                        </p>

                        <p>
                            <span className="text-[#637777]">Role:</span> CTO
                        </p>

                        <p>
                            <span className="text-[#637777]">Focus:</span>{" "}
                            Software Architecture
                        </p>

                        <p>
                            <span className="invisible">Focus:</span>{" "}
                            Engineering Leadership
                        </p>
                    </div>
                )}

                {/* SOCIAL */}
                {isSocialCommandVisible && (
                    <TerminalPrompt command="cat ~/.social" active={stage === SHELL_STAGE.SOCIAL && !isProcessing}/>
                )}

                {isSocialOutputVisible && (
                    <div className="my-5 space-y-1">
                        {linkedinUrl && (
                            <p className="break-words">
                                <span className="text-[#82aaff]">linkedin</span>

                                <span className="text-[#637777]">=</span>

                                <a
                                    href={linkedinUrl}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="break-all text-[#c792ea] underline decoration-transparent underline-offset-4 transition-colors hover:text-[#d6deeb] hover:decoration-current"
                                >
                                    {linkedinUrl}
                                </a>
                            </p>
                        )}

                        {githubUrl && (
                            <p className="break-words">
                                <span className="text-[#82aaff]">github</span>

                                <span className="text-[#637777]">=</span>

                                <a
                                    href={githubUrl}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="break-all text-[#c792ea] underline decoration-transparent underline-offset-4 transition-colors hover:text-[#d6deeb] hover:decoration-current"
                                >
                                    {githubUrl}
                                </a>
                            </p>
                        )}
                    </div>
                )}

                {/* LOGOUT */}
                {isLogoutCommandVisible && (
                    <TerminalPrompt
                        command="logout"
                        active={stage === SHELL_STAGE.LOGOUT && !isProcessing}
                    />
                )}

                {isLogoutOutputVisible && (
                    <div className="mt-5">
                        <p className="text-[#637777]">Saving session...</p>

                        <p>Session closed.</p>

                        <p className="mt-4 hidden md:block">
                            Press <span className="text-[#ffcc66]">Enter</span>{" "}
                            to return to amirsaeedkomjani.ir
                        </p>

                        <p className="hidden text-[#637777] md:block">
                            Press ESC anytime to exit.
                        </p>

                        <p className="mt-4 md:hidden">
                            Tap <span className="text-[#ffcc66]">Home</span> to
                            return to amirsaeedkomjani.ir
                        </p>

                        <p className="text-[#637777] md:hidden">
                            Tap Exit anytime to leave.
                        </p>
                    </div>
                )}

                {/* Mobile controls */}
                <div className="mt-6 flex gap-3 md:hidden">
                    <button
                        type="button"
                        onClick={executeCurrentCommand}
                        disabled={isProcessing}
                        className="rounded-md border border-[#82aaff]/40 px-4 py-2 text-[#82aaff] transition-colors active:bg-[#82aaff]/10 disabled:cursor-not-allowed disabled:opacity-40"
                    >
                        {stage === SHELL_STAGE.COMPLETE ? "Home" : "Run"}
                    </button>

                    <button
                        type="button"
                        onClick={exitShell}
                        className="rounded-md border border-white/10 px-4 py-2 text-[#637777] transition-colors active:text-[#d6deeb]"
                    >
                        Exit
                    </button>
                </div>
            </div>
        </TerminalWindow>
    );
}

export default SocialTerminal;
