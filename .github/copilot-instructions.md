# Copilot Repository Instructions

## Strict WordPress & Elementor Preservation
- **DO NOT delete, strip, or refactor legacy WordPress, Elementor, WooCommerce, or 10Web code.**
- Never remove HTML classes or attributes starting with `elementor-`, `woocommerce-`, `tbdemo_`, `page-id-`, or `theme-`.
- Preserve all inline CSS variables (such as `--e-global-typography-...`) and script tags.
- Do not replace WordPress HTML/DOM structures with simplified HTML5 or clean PHP unless explicitly requested.
- When making edits, only update the target text content, links, or specific styling parameters, while keeping all surrounding WordPress wrapper elements fully intact.
## Hard Guardrails & Anti-Accident Checks
- **Refusal Warning:** If a prompt explicitly or implicitly asks to strip, delete, or refactor WordPress, Elementor, or 10Web code, STOP and warn the user that this violates the repository instructions before generating any code.
- **Read-Only HTML Structure:** Treat all HTML tags, wrappers, classes, IDs, and script references as READ-ONLY. You are ONLY authorized to edit text content inside tags, `href`/`src` attributes, or specific CSS properties.
- **Pre-Output Self-Check:** Before returning any code edit, verify that no `elementor-`, `woocommerce-`, or `theme-` classes were removed from the original snippet.
## Agent & Workspace Scope Controls
- **No Unsolicited Terminal Commands:** Do NOT execute terminal commands, background scans, or TODO sweeps unless explicitly instructed in the prompt.
- **Strict File Scope:** ONLY edit the file explicitly requested or open in the active editor tab. Do not autonomously open or edit other project files.
- **No Autonomous Git Operations:** Do NOT perform `git commit`, `git push`, or branch updates without explicit instruction.
- **Layout Locking:** Keep all header navigation layouts and button placements intact (such as keeping the 'Home' button positioned on the far right).