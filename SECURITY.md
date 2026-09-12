# Security policy

## Supported versions

Security fixes are applied to the latest minor release line.

## What this package exposes

Filament Laravel Tribute Template is a stylesheet and a panel plugin. It ships
no runtime JavaScript, registers no route, reads no request input and touches
no database. What it does at boot is register a colour palette, a font family
and an optional sidebar width on the Filament panel, and publish a CSS entry
point into the host application.

The stylesheet is compiled by the host application's own Vite and Tailwind
pipeline, so it enters your build like any other source dependency. A test in
the suite fails if any source file emits a `<script` tag, which keeps the
package compatible with a strict Content Security Policy.

## Reporting a vulnerability

Please do not open public issues for security reports. Email
alexandre@laboiteacode.fr with:

- a description of the vulnerability and its impact;
- reproduction steps or a proof of concept;
- affected versions.

You will receive an acknowledgement within a few days. Please allow a
reasonable window for a fix and coordinated disclosure before publishing
details.
