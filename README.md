# RDML Transpiler

Transpiler for the RDML programing language. 

## Target Languages

The initial target language is PHP.
Possible future language targets include C# (OO) and Elixir (functional). 

## Compiler Tool Chain

This project is a based upon the work of Viewpoints Research Institute (https://vpri.org), specifically the OMeta JS libary (https://b-studios.de/ometa-js). 
Ometa is a PEG parser which unifies and simplifies the typical compiler tool change.

## Why This Exists

The goal of this project is to provide the ability to convert legacy 4GL RDML code into a more modern language while preserving symantic behavior and compatability where possible.

## RDML/RDMLX background

RDML is a language from a proprietary 4GL tool launched in the late 1980s (think Synon). Over the years it expanded from RDML (procedural) to RDMLX (OO), supported 5250 batch and interactive intefaces (AS/400 and IBMi), browser interfaces, and Client Server applications running on windows. 

The value proposition of this tool was to write in a single language (RDML) instead of needing to know multiple languages. Supported platforms were AS/400 (IBMi today) and Windows. Linux was never a fully supported platform. Its roots and strength were IBMi and brought web interfaces to a platform where there were not always a lot of options. 

The web interface stack went through at 3 stages starting with WEBEVENT (think php templates), then WAM (XML to XSLT transformation), and finally VL Web which was api based on the backend with a RDMLX runtime engine in the browser meant to work like a Windows client server application (essentially a SPA but running RDML code instead of JavaScript). 

Other extensions included a 5250 wrapper which could be embedded in a web page or windows application, a java based middleware product, and an app store wrapper for browser based applications. The base product also included a business rules engine (think drools in java) but tightly coupled with the database and the database could be extended and modeled in this tool

## Goals

The task of converting an application written in the above mentioned tool (or family of tools) into a fully functional application in a different language using a different architectural design is non trivial and beyond the scope of this project. 

The point of this project is to focus on the web based aspects and extract business logic where possible. All of the backend business logic which is not interface related or inside the rules engine is by necessity RDML or RDMLX (OO based syntax) and should be convertable into any language. Just like there are many companies running COBOL applications there are many running RDML or RDMLX based applications which may need to modernize.

The web interface aspect of this project seeks to implement a simple template based SSR application which is compatible with PHP. This is compatible with both WebEvent and WAM applications. VL Web is a SPA, so the conversion would not be as straight foward. The current implementation uses the template engine TinyButStrong (https://www.tinybutstrong.com) for demonstration purposes, but this could be compatible with any number of frameworks including HTMX, HyperScript, or Alpine.js. 

## Request for features

This is currently a proof of concept. To request a feature or report a bug, please open an issue. Until version 1.0 is released, non backwards compatible behaviour can occur.

## Test runner

A simple batch test runner compiles cases under `private/tests/cases/`, compares generated outputs against golden files, and supports `--only` and `--update` flags. See the full usage and conventions:

[private/tests/testUsage.md](./private/tests/TestUsage.md)




